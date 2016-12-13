<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\adjust_color;
use SSNepenthe\ColorUtils\Transformers\AdjustColor;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb#L541
 */
class AdjustColorTest extends TransformerTestCase
{
    public function test_it_can_adjust_hsl_colors()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(180, 30, 90)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg)"))
            [
                'transformer' => new AdjustColor(
                    ['hue' => 60]
                ),
                'result' => [180, 30, 90],
            ],
            // assert_equal(evaluate("hsl(120, 50, 90)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $saturation: 20%)"))
            [
                'transformer' => new AdjustColor(
                    ['saturation' => 20]
                ),
                'result' => [120, 50, 90],
            ],
            // assert_equal(evaluate("hsl(120, 30, 60)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $lightness: -30%)"))
            [
                'transformer' => new AdjustColor(
                    ['lightness' => -30]
                ),
                'result' => [120, 30, 60],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_adjust_rgb_colors()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(15, 20, 30)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $red: 5)"))
            [
                'transformer' => new AdjustColor(
                    ['red' => 5]
                ),
                'result' => [15, 20, 30],
            ],
            // assert_equal(evaluate("rgb(10, 15, 30)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $green: -5)"))
            [
                'transformer' => new AdjustColor(
                    ['green' => -5]
                ),
                'result' => [10, 15, 30],
            ],
            // assert_equal(evaluate("rgb(10, 20, 40)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $blue: 10)"))
            [
                'transformer' => new AdjustColor(
                    ['blue' => 10]
                ),
                'result' => [10, 20, 40],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_adjust_alpha_values()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsla(120, 30, 90, 0.65)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $alpha: -0.35)"))
            [
                'transformer' => new AdjustColor(
                    ['alpha' => -0.35]
                ),
                'result' => [120, 30, 90, 0.65],
            ],
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromRgb(10, 20, 30, 0.4);

        $tests = [
            // assert_equal(evaluate("rgba(10, 20, 30, 0.9)"),
            // evaluate("adjust-color(rgba(10, 20, 30, 0.4), $alpha: 0.5)"))
            [
                'transformer' => new AdjustColor(
                    ['alpha' => 0.5]
                ),
                'result' => [10, 20, 30, 0.9],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_adjust_multiple_hsl_attributes_at_once()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(180, 20, 90)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg, $saturation: -10%)"))
            [
                'transformer' => new AdjustColor(
                    ['hue' => 60, 'saturation' => -10]
                ),
                'result' => [180, 20, 90],
            ],
            // assert_equal(evaluate("hsl(180, 20, 95)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg, $saturation: -10%, $lightness: 5%)"))
            [
                'transformer' => new AdjustColor(
                    ['hue' => 60, 'saturation' => -10, 'lightness' => 5]
                ),
                'result' => [180, 20, 95],
            ],
            // assert_equal(evaluate("hsla(120, 20, 95, 0.3)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $saturation: -10%, $lightness: 5%, $alpha: -0.7)"))
            [
                'transformer' => new AdjustColor(
                    ['saturation' => -10, 'lightness' => 5, 'alpha' => -0.7]
                ),
                'result' => [120, 20, 95, 0.3],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_adjust_multiple_rgb_attributes_at_once()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(15, 20, 29)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $red: 5, $blue: -1)"))
            [
                'transformer' => new AdjustColor(
                    ['red' => 5, 'blue' => -1]
                ),
                'result' => [15, 20, 29],
            ],
            // assert_equal(evaluate("rgb(15, 45, 29)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $red: 5, $green: 25, $blue: -1)"))
            [
                'transformer' => new AdjustColor(
                    ['red' => 5, 'green' => 25, 'blue' => -1]
                ),
                'result' => [15, 45, 29],
            ],
            // assert_equal(evaluate("rgba(10, 25, 29, 0.7)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $green: 5, $blue: -1, $alpha: -0.3)"))
            [
                'transformer' => new AdjustColor(
                    ['green' => 5, 'blue' => -1, 'alpha' => -0.3]
                ),
                'result' => [10, 25, 29, 0.7],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    /**
     * Technically restrictions are handled in the Hsl class but it can't really hurt
     * to have some extra tests for it.
     */
    public function test_it_honors_hsl_range_restrictions()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal(evaluate("hsl(120, 30, 90)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $hue: 720deg)"))
            [
                'transformer' => new AdjustColor(
                    ['hue' => 720]
                ),
                'result' => [120, 30, 90],
            ],
            // assert_equal(evaluate("hsl(120, 0, 90)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $saturation: -90%)"))
            [
                'transformer' => new AdjustColor(
                    ['saturation' => -90]
                ),
                'result' => [120, 0, 90],
            ],
            // assert_equal(evaluate("hsl(120, 30, 100)"),
            // evaluate("adjust-color(hsl(120, 30, 90), $lightness: 30%)"))
            [
                'transformer' => new AdjustColor(
                    ['lightness' => 30]
                ),
                'result' => [120, 30, 100],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    /**
     * See note on hsl restriction test.
     */
    public function test_it_honors_rgb_range_restriction()
    {
        $color = Color::fromRgb(10, 20, 30);

        $tests = [
            // assert_equal(evaluate("rgb(255, 20, 30)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $red: 250)"))
            [
                'transformer' => new AdjustColor(
                    ['red' => 250]
                ),
                'result' => [255, 20, 30],
            ],
            // assert_equal(evaluate("rgb(10, 0, 30)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $green: -30)"))
            [
                'transformer' => new AdjustColor(
                    ['green' => -30]
                ),
                'result' => [10, 0, 30],
            ],
            // assert_equal(evaluate("rgb(10, 20, 0)"),
            // evaluate("adjust-color(rgb(10, 20, 30), $blue: -40)"))
            [
                'transformer' => new AdjustColor(
                    ['blue' => -40]
                ),
                'result' => [10, 20, 0],
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new AdjustColor(['lightness' => 50]);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 50],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = adjust_color(Color::fromString('black'), ['lightness' => 50]);
        $this->assertEquals([0, 0, 50], $color->getHsl()->toArray());
    }
}
