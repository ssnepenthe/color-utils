<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\desaturate;
use SSNepenthe\ColorUtils\Transformers\Desaturate;

class DesaturateTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_desaturate_hsl_colors()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal("#e3e8e3", evaluate("desaturate(hsl(120, 30, 90), 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('#e3e8e3', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_desaturate_hex_colors()
    {
        $c = Color::fromString('#855');

        // assert_equal("#726b6b", evaluate("desaturate(#855, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('#726b6b', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_desaturate_rgb_colors()
    {
        $c = Color::fromRgb(136, 85, 85, 0.5);

        // assert_equal("rgba(114, 107, 107, 0.5)", evaluate("desaturate(rgba(136, 85, 85, 0.5), 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('rgba(114, 107, 107, 0.5)', $t->transform($c));
    }

    public function it_cant_desaturate_shades_of_gray()
    {
        $c = Color::fromString('#000');

        // assert_equal("black", evaluate("desaturate(#000, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('black', $t->transform($c)->getName());

        $c = Color::fromString('#fff');

        // assert_equal("white", evaluate("desaturate(#fff, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    public function test_it_handles_the_extremes()
    {
        $c = Color::fromString('#8a8');

        // assert_equal("#999999", evaluate("desaturate(#8a8, 100%)"))
        $t = new Desaturate(100);
        $this->assertEquals('#999999', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("#88aa88", evaluate("desaturate(#8a8, 0%)"))
        $t = new Desaturate(0);
        $this->assertEquals('#88aa88', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('#f00'),
            new Rgb(255, 0, 0),
            Hsl::fromString('hsl(0, 100%, 50%)'),
        ];

        $transformer = new Desaturate(5);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 95, 50],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = desaturate(Color::fromString('#f00'), 5);
        $this->assertEquals([0, 95, 50], $color->getHsl()->toArray());
    }
}
