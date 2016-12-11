<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ChangeColor;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb#L541
 */
class ChangeColorTest extends TransformerTestCase
{
    public function test_it_can_change_hsl_colors()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(195, 30, 90)"),
            // evaluate("change-color(hsl(120, 30, 90), $hue: 195deg)"))
            [
                'transformer' => new ChangeColor(
                    ['hue' => 195]
                ),
                'result' => [195, 30, 90],
            ],
            // assert_equal(evaluate("hsl(120, 50, 90)"),
            // evaluate("change-color(hsl(120, 30, 90), $saturation: 50%)"))
            [
                'transformer' => new ChangeColor(
                    ['saturation' => 50]
                ),
                'result' => [120, 50, 90],
            ],
            // assert_equal(evaluate("hsl(120, 30, 40)"),
            // evaluate("change-color(hsl(120, 30, 90), $lightness: 40%)"))
            [
                'transformer' => new ChangeColor(
                    ['lightness' => 40]
                ),
                'result' => [120, 30, 40],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_change_rgb_colors()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(123, 20, 30)"),
            // evaluate("change-color(rgb(10, 20, 30), $red: 123)"))
            [
                'transformer' => new ChangeColor(
                    ['red' => 123]
                ),
                'result' => [123, 20, 30],
            ],
            // assert_equal(evaluate("rgb(10, 234, 30)"),
            // evaluate("change-color(rgb(10, 20, 30), $green: 234)"))
            [
                'transformer' => new ChangeColor(
                    ['green' => 234]
                ),
                'result' => [10, 234, 30],
            ],
            // assert_equal(evaluate("rgb(10, 20, 198)"),
            // evaluate("change-color(rgb(10, 20, 30), $blue: 198)"))
            [
                'transformer' => new ChangeColor(
                    ['blue' => 198]
                ),
                'result' => [10, 20, 198],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_change_alpha_values()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgba(10, 20, 30, 0.76)"),
            // evaluate("change-color(rgb(10, 20, 30), $alpha: 0.76)"))
            [
                'transformer' => new ChangeColor(
                    ['alpha' => 0.76]
                ),
                'result' => [10, 20, 30, 0.76],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_change_multiple_hsl_attributes_at_once()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(56, 30, 47)"),
            // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%)"))
            [
                'transformer' => new ChangeColor(
                    ['hue' => 56, 'lightness' => 47]
                ),
                'result' => [56, 30, 47],
            ],
            // assert_equal(evaluate("hsla(56, 30, 47, 0.9)"),
            // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%, $alpha: 0.9)"))
            [
                'transformer' => new ChangeColor(
                    ['hue' => 56, 'lightness' => 47, 'alpha' => 0.9]
                ),
                'result' => [56, 30, 47, 0.9],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    /**
     * Untested in SASS...
     */
    public function test_it_can_change_multiple_rgb_attributes_at_once()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            [
                'transformer' => new ChangeColor(
                    ['red' => 100, 'green' => 200]
                ),
                'result' => [100, 200, 30],
            ],
            [
                'transformer' => new ChangeColor(
                    ['red' => 100, 'green' => 200, 'alpha' => 0.9]
                ),
                'result' => [100, 200, 30, 0.9],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }
}
