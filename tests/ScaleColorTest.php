<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ScaleColor;

/**
 * SASS tests some of these results to the decimal but our Rgb and Hsl classes round
 * these off for us so we test to the rounded value.
 */
class ScaleColorTest extends TransformerTestCase
{
    public function test_it_can_scale_hsl_colors()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(120, 51, 90)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => 30]
                ),
                'result' => [120, 51, 90],
            ],
            // assert_equal(evaluate("hsl(120, 30, 76.5)"),
            // evaluate("scale-color(hsl(120, 30, 90), $lightness: -15%)"))
            [
                'transformer' => new ScaleColor(
                    ['lightness' => -15]
                ),
                'result' => [120, 30, 77],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_scale_rgb_colors()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(157, 20, 30)"),
            // evaluate("scale-color(rgb(10, 20, 30), $red: 60%)"))
            [
                'transformer' => new ScaleColor(
                    ['red' => 60]
                ),
                'result' => [157, 20, 30],
            ],
            // assert_equal(evaluate("rgb(10, 38.8, 30)"),
            // evaluate("scale-color(rgb(10, 20, 30), $green: 8%)"))
            [
                'transformer' => new ScaleColor(
                    ['green' => 8]
                ),
                'result' => [10, 39, 30],
            ],
            // assert_equal(evaluate("rgb(10, 20, 20)"),
            // evaluate("scale-color(rgb(10, 20, 30), $blue: -(1/3)*100%)"))
            [
                'transformer' => new ScaleColor(
                    ['blue' => -(1 / 3) * 100]
                ),
                'result' => [10, 20, 20],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_scale_alpha_attributes()
    {
        // assert_equal(evaluate("hsla(120, 30, 90, 0.86)"),
        // evaluate("scale-color(hsl(120, 30, 90), $alpha: -14%)"))
        $transformer = new ScaleColor(['alpha' => -14]);
        $this->assertEquals(
            [120, 30, 90, 0.86],
            $transformer->transform(Color::fromHsl(120, 30, 90))->toArray()
        );

        // assert_equal(evaluate("rgba(10, 20, 30, 0.82)"),
        // evaluate("scale-color(rgba(10, 20, 30, 0.8), $alpha: 10%)"))
        $transformer = new ScaleColor(['alpha' => 10]);
        $this->assertEquals(
            [10, 20, 30, 0.82],
            $transformer->transform(Color::fromRgb(10, 20, 30, 0.8))->toArray()
        );
    }

    public function test_it_can_scale_multiple_hsl_attributes()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(120, 51, 76.5)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%, $lightness: -15%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => 30, 'lightness' => -15]
                ),
                'result' => [120, 51, 77],
            ],
            // assert_equal(evaluate("hsla(120, 51, 90, 0.2)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%, $alpha: -80%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => 30, 'alpha' => -80]
                ),
                'result' => [120, 51, 90, 0.2],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_scale_multiple_rgb_attributes()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(157, 38.8, 30)"),
            // evaluate("scale-color(rgb(10, 20, 30), $red: 60%, $green: 8%)"))
            [
                'transformer' => new ScaleColor(
                    ['red' => 60, 'green' => 8]
                ),
                'result' => [157, 39, 30],
            ],
            // assert_equal(evaluate("rgb(157, 38.8, 20)"),
            // evaluate("scale-color(rgb(10, 20, 30), $red: 60%, $green: 8%, $blue: -(1/3)*100%)"))
            [
                'transformer' => new ScaleColor(
                    ['red' => 60, 'green' => 8, 'blue' => -(1 / 3) * 100]
                ),
                'result' => [157, 39, 20],
            ],
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromRgb(10, 20, 30, 0.5);

        $tests = [
            // assert_equal(evaluate("rgba(10, 38.8, 20, 0.55)"),
            // evaluate("scale-color(rgba(10, 20, 30, 0.5), $green: 8%, $blue: -(1/3)*100%, $alpha: 10%)"))
            [
                'transformer' => new ScaleColor(
                    ['green' => 8, 'blue' => -(1 / 3) * 100, 'alpha' => 10]
                ),
                'result' => [10, 39, 20, 0.55],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_handle_extremes()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(120, 100, 90)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: 100%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => 100]
                ),
                'result' => [120, 100, 90],
            ],
            // assert_equal(evaluate("hsl(120, 30, 90)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: 0%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => 0]
                ),
                'result' => [120, 30, 90],
            ],
            // assert_equal(evaluate("hsl(120, 0, 90)"),
            // evaluate("scale-color(hsl(120, 30, 90), $saturation: -100%)"))
            [
                'transformer' => new ScaleColor(
                    ['saturation' => -100]
                ),
                'result' => [120, 0, 90],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }
}
