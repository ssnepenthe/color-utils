<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Darken;

class DarkenTest extends TransformerTestCase
{
    public function test_it_can_darken_hsl_colors()
    {
        $color = Color::fromHsl(25, 100, 80);

        $tests = [
            // assert_equal("#ff6a00", evaluate("darken(hsl(25, 100, 80), 30%)"))
            ['transformer' => new Darken(30), 'result' => [25, 100, 50]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_darken_hex_colors()
    {
        $color = Color::fromString('#800');

        $tests = [
            // @todo
            // assert_equal("#220000", evaluate("darken(#800, 20%)"))
            ['transformer' => new Darken(20), 'result' => [36, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_darken_rgb_colors()
    {
        $color = Color::fromRgb(136, 0, 0, 0.5);

        $tests = [
            // @todo
            // assert_equal("rgba(34, 0, 0, 0.5)", evaluate("darken(rgba(136, 0, 0, 0.5), 20%)"))
            ['transformer' => new Darken(20), 'result' => [36, 0, 0, 0.5]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_only_go_as_dark_as_black()
    {
        $color = Color::fromString('#000');

        $tests = [
            // assert_equal("black", evaluate("darken(#000, 20%)"))
            ['transformer' => new Darken(20), 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromString('#800');

        $tests = [
            // assert_equal("black", evaluate("darken(#800, 100%)"))
            ['transformer' => new Darken(100), 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_darkening_by_0_does_not_change_color()
    {
        $color = Color::fromString('#800');

        $tests = [
            // @todo
            // assert_equal("#880000", evaluate("darken(#800, 0%)"))
            ['transformer' => new Darken(0), 'result' => [138, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
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
}
