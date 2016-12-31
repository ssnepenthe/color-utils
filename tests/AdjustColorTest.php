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
class AdjustColorTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_adjust_hsl_colors()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(180, 30, 90)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg)"))
        $t = new AdjustColor(['hue' => 60]);
        $this->assertEquals('hsl(180, 30%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 50, 90)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $saturation: 20%)"))
        $t = new AdjustColor(['saturation' => 20]);
        $this->assertEquals('hsl(120, 50%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 30, 60)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $lightness: -30%)"))
        $t = new AdjustColor(['lightness' => -30]);
        $this->assertEquals('hsl(120, 30%, 60%)', $t->transform($c));
    }

    public function test_it_can_adjust_rgb_colors()
    {
        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgb(15, 20, 30)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $red: 5)"))
        $t = new AdjustColor(['red' => 5]);
        $this->assertEquals('rgb(15, 20, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 15, 30)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $green: -5)"))
        $t = new AdjustColor(['green' => -5]);
        $this->assertEquals('rgb(10, 15, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 20, 40)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $blue: 10)"))
        $t = new AdjustColor(['blue' => 10]);
        $this->assertEquals('rgb(10, 20, 40)', $t->transform($c));
    }

    public function test_it_can_adjust_alpha_values()
    {
        // assert_equal(evaluate("hsla(120, 30, 90, 0.65)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $alpha: -0.35)"))
        $c = Color::fromHsl(120, 30, 90);
        $t = new AdjustColor(['alpha' => -0.35]);
        $this->assertEquals(
            'hsla(120, 30%, 90%, 0.65)',
            $t->transform($c)
        );

        // assert_equal(evaluate("rgba(10, 20, 30, 0.9)"),
        // evaluate("adjust-color(rgba(10, 20, 30, 0.4), $alpha: 0.5)"))
        $c = Color::fromRgb(10, 20, 30, 0.4);
        $t = new AdjustColor(['alpha' => 0.5]);
        $this->assertEquals('rgba(10, 20, 30, 0.9)', $t->transform($c));
    }

    public function test_it_can_adjust_multiple_hsl_attributes_at_once()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(180, 20, 90)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg, $saturation: -10%)"))
        $t = new AdjustColor(['hue' => 60, 'saturation' => -10]);
        $this->assertEquals('hsl(180, 20%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(180, 20, 95)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $hue: 60deg, $saturation: -10%, $lightness: 5%)"))
        $t = new AdjustColor(['hue' => 60, 'saturation' => -10, 'lightness' => 5]);
        $this->assertEquals('hsl(180, 20%, 95%)', $t->transform($c));

        // assert_equal(evaluate("hsla(120, 20, 95, 0.3)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $saturation: -10%, $lightness: 5%, $alpha: -0.7)"))
        $t = new AdjustColor([
            'saturation' => -10,
            'lightness' => 5,
            'alpha' => -0.7
        ]);
        $this->assertEquals(
            'hsla(120, 20%, 95%, 0.3)',
            $t->transform($c)
        );
    }

    public function test_it_can_adjust_multiple_rgb_attributes_at_once()
    {
        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgb(15, 20, 29)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $red: 5, $blue: -1)"))
        $t = new AdjustColor(['red' => 5, 'blue' => -1]);
        $this->assertEquals('rgb(15, 20, 29)', $t->transform($c));

        // assert_equal(evaluate("rgb(15, 45, 29)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $red: 5, $green: 25, $blue: -1)"))
        $t = new AdjustColor(['red' => 5, 'green' => 25, 'blue' => -1]);
        $this->assertEquals('rgb(15, 45, 29)', $t->transform($c));

        // assert_equal(evaluate("rgba(10, 25, 29, 0.7)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $green: 5, $blue: -1, $alpha: -0.3)"))
        $t = new AdjustColor(['green' => 5, 'blue' => -1, 'alpha' => -0.3]);
        $this->assertEquals('rgba(10, 25, 29, 0.7)', $t->transform($c));
    }

    /**
     * Technically restrictions are handled in the Hsl class but it can't really hurt
     * to have some extra tests for it.
     */
    public function test_it_honors_hsl_range_restrictions()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(120, 30, 90)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $hue: 720deg)"))
        $t = new AdjustColor(['hue' => 720]);
        $this->assertEquals('hsl(120, 30%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 0, 90)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $saturation: -90%)"))
        $t = new AdjustColor(['saturation' => -90]);
        $this->assertEquals('hsl(120, 0%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 30, 100)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $lightness: 30%)"))
        $t = new AdjustColor(['lightness' => 30]);
        $this->assertEquals('hsl(120, 30%, 100%)', $t->transform($c));
    }

    /**
     * See note on hsl restriction test.
     */
    public function test_it_honors_rgb_range_restriction()
    {
        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgb(255, 20, 30)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $red: 250)"))
        $t = new AdjustColor(['red' => 250]);
        $this->assertEquals('rgb(255, 20, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 0, 30)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $green: -30)"))
        $t = new AdjustColor(['green' => -30]);
        $this->assertEquals('rgb(10, 0, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 20, 0)"),
        // evaluate("adjust-color(rgb(10, 20, 30), $blue: -40)"))
        $t = new AdjustColor(['blue' => -40]);
        $this->assertEquals('rgb(10, 20, 0)', $t->transform($c));
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $t = new AdjustColor(['lightness' => 50]);

        foreach ($colors as $c) {
            $this->assertEquals(
                [0, 0, 50],
                $t->transform($c)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $this->assertEquals(
            [0, 0, 50],
            adjust_color(
                Color::fromString('black'),
                ['lightness' => 50]
            )->getHsl()->toArray()
        );
    }
}
