<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\change_color;
use SSNepenthe\ColorUtils\Transformers\ChangeColor;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb#L541
 */
class ChangeColorTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_change_hsl_colors()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(195, 30, 90)"),
        // evaluate("change-color(hsl(120, 30, 90), $hue: 195deg)"))
        $t = new ChangeColor(['hue' => 195]);
        $this->assertEquals('hsl(195, 30%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 50, 90)"),
        // evaluate("change-color(hsl(120, 30, 90), $saturation: 50%)"))
        $t = new ChangeColor(['saturation' => 50]);
        $this->assertEquals('hsl(120, 50%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 30, 40)"),
        // evaluate("change-color(hsl(120, 30, 90), $lightness: 40%)"))
        $t = new ChangeColor(['lightness' => 40]);
        $this->assertEquals('hsl(120, 30%, 40%)', $t->transform($c));
    }

    public function test_it_can_change_rgb_colors()
    {
        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgb(123, 20, 30)"),
        // evaluate("change-color(rgb(10, 20, 30), $red: 123)"))
        $t = new ChangeColor(['red' => 123]);
        $this->assertEquals('rgb(123, 20, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 234, 30)"),
        // evaluate("change-color(rgb(10, 20, 30), $green: 234)"))
        $t = new ChangeColor(['green' => 234]);
        $this->assertEquals('rgb(10, 234, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 20, 198)"),
        // evaluate("change-color(rgb(10, 20, 30), $blue: 198)"))
        $t = new ChangeColor(['blue' => 198]);
        $this->assertEquals('rgb(10, 20, 198)', $t->transform($c));
    }

    public function test_it_can_change_alpha_values()
    {
        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgba(10, 20, 30, 0.76)"),
        // evaluate("change-color(rgb(10, 20, 30), $alpha: 0.76)"))
        $t = new ChangeColor(['alpha' => 0.76]);
        $this->assertEquals('rgba(10, 20, 30, 0.76)', $t->transform($c));
    }

    public function test_it_can_change_multiple_hsl_attributes_at_once()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(56, 30, 47)"),
        // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%)"))
        $t = new ChangeColor(['hue' => 56, 'lightness' => 47]);
        $this->assertEquals('hsl(56, 30%, 47%)', $t->transform($c));

        // assert_equal(evaluate("hsla(56, 30, 47, 0.9)"),
        // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%, $alpha: 0.9)"))
        $t = new ChangeColor(['hue' => 56, 'lightness' => 47, 'alpha' => 0.9]);
        $this->assertEquals('hsla(56, 30%, 47%, 0.9)', $t->transform($c));
    }

    public function test_it_can_change_multiple_rgb_attributes_at_once()
    {
        $c = Color::fromRgb(10, 20, 30);

        $t = new ChangeColor(['red' => 100, 'green' => 200]);
        $this->assertEquals([100, 200, 30], $t->transform($c)->toArray());

        $t = new ChangeColor(['red' => 100, 'green' => 200, 'alpha' => 0.9]);
        $this->assertEquals([100, 200, 30, 0.9], $t->transform($c)->toArray());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $t = new ChangeColor(['lightness' => 50]);

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
            change_color(
                Color::fromString('black'),
                ['lightness' => 50]
            )->getHsl()->toArray()
        );
    }
}
