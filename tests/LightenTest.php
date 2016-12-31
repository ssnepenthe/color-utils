<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\lighten;
use SSNepenthe\ColorUtils\Transformers\Lighten;

class LightenTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_lighten_hex_colors()
    {
        $c = Color::fromString('#800');

        // @todo
        // assert_equal("#ee0000", evaluate("lighten(#800, 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('#f00000', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("white", evaluate("lighten(#800, 100%)"))
        $t = new Lighten(100);
        $this->assertEquals('white', $t->transform($c)->getName());

        // @todo
        // assert_equal("#880000", evaluate("lighten(#800, 0%)"))
        $t = new Lighten(0);
        $this->assertEquals('#8a0000', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_lighten_hsl_colors()
    {
        $c = Color::fromHsl(0, 0, 0);

        // assert_equal("#4d4d4d", evaluate("lighten(hsl(0, 0, 0), 30%)"))
        $t = new Lighten(30);
        $this->assertEquals('#4d4d4d', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_lighten_rgb_colors()
    {
        $c = Color::fromRgb(136, 0, 0, 0.5);

        // @todo
        // assert_equal("rgba(238, 0, 0, 0.5)", evaluate("lighten(rgba(136, 0, 0, 0.5), 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('rgba(240, 0, 0, 0.5)', $t->transform($c));
    }

    public function test_it_can_only_go_as_light_as_white()
    {
        $c = Color::fromString('white');

        // assert_equal("white", evaluate("lighten(#fff, 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new Lighten(50);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 50],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = lighten(Color::fromString('black'), 50);
        $this->assertEquals([0, 0, 50], $color->getHsl()->toArray());
    }
}
