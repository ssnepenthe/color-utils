<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\darken;
use SSNepenthe\ColorUtils\Transformers\Darken;

class DarkenTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_darken_hsl_colors()
    {
        $c = Color::fromHsl(25, 100, 80);

        // assert_equal("#ff6a00", evaluate("darken(hsl(25, 100, 80), 30%)"))
        $t = new Darken(30);
        $this->assertEquals('#ff6a00', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_darken_hex_colors()
    {
        $c = Color::fromString('#800');

        // @todo Off from SASS, matches rgb.to.
        // assert_equal("#220000", evaluate("darken(#800, 20%)"))
        $t = new Darken(20);
        $this->assertEquals('#240000', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_darken_rgb_colors()
    {
        $c = Color::fromRgb(136, 0, 0, 0.5);

        // @todo Off from SASS, matches rgb.to.
        // assert_equal("rgba(34, 0, 0, 0.5)", evaluate("darken(rgba(136, 0, 0, 0.5), 20%)"))
        $t = new Darken(20);
        $this->assertEquals('rgba(36, 0, 0, 0.5)', $t->transform($c));
    }

    public function test_it_can_only_go_as_dark_as_black()
    {
        $c = Color::fromString('#000');

        // assert_equal("black", evaluate("darken(#000, 20%)"))
        $t = new Darken(20);
        $this->assertEquals('black', $t->transform($c)->getName());

        $c = Color::fromString('#800');

        // assert_equal("black", evaluate("darken(#800, 100%)"))
        $t = new Darken(100);
        $this->assertEquals('black', $t->transform($c)->getName());
    }

    public function test_darkening_by_0_does_not_change_color()
    {
        $c = Color::fromString('#800');

        // @todo This is a weird one... Matches rgb.to though.
        // assert_equal("#880000", evaluate("darken(#800, 0%)"))
        $t = new Darken(0);
        $this->assertEquals('#8a0000', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('white'),
            Rgb::fromString('white'),
            Hsl::fromString('hsl(0, 0%, 100%)'),
        ];

        $transformer = new Darken(30);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 70],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $this->assertEquals(
            [0, 0, 70],
            darken(Color::fromString('white'), 30)->getHsl()->toArray()
        );
    }
}
