<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Saturate;

class SaturateTest extends TransformerTestCase
{
    public function test_it_can_saturate_hsl_colors()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal("#d9f2d9", evaluate("saturate(hsl(120, 30, 90), 20%)"))
            ['transformer' => new Saturate(20), 'result' => [120, 50, 90]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_saturate_hex_colors()
    {
        $color = Color::fromString('#855');

        $tests = [
            // @todo Hue off by one from SASS.
            // assert_equal("#9e3f3f", evaluate("saturate(#855, 20%)"))
            ['transformer' => new Saturate(20), 'result' => [157, 63, 63]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_saturating_shades_of_gray_doesnt_change_the_color()
    {
        $color = Color::fromString('#000');

        $tests = [
            // assert_equal("black", evaluate("saturate(#000, 20%)"))
            ['transformer' => new Saturate(20), 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromString('#fff');

        $tests = [
            // assert_equal("white", evaluate("saturate(#fff, 20%)"))
            ['transformer' => new Saturate(20), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_honors_hsl_ranges_when_saturating_colors()
    {
        $color = Color::fromString('#8a8');

        $tests = [
            // assert_equal("#33ff33", evaluate("saturate(#8a8, 100%)"))
            ['transformer' => new Saturate(100), 'result' => [51, 255, 51]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_doesnt_change_the_color_when_given_a_zero_amount()
    {
        $color = Color::fromString('#8a8');

        $tests = [
            // assert_equal("#88aa88", evaluate("saturate(#8a8, 0%)"))
            ['transformer' => new Saturate(0), 'result' => [136, 170, 136]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_saturate_rgb_colors()
    {
        $color = Color::fromRgb(136, 85, 85, 0.5);

        $tests = [
            // assert_equal("rgba(158, 63, 63, 0.5)", evaluate("saturate(rgba(136, 85, 85, 0.5), 20%)"))
            ['transformer' => new Saturate(20), 'result' => [157, 63, 63, 0.5]]
        ];

        $this->runTransformerTests($color, $tests);
    }
}

