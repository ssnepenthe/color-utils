<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ChangeColor;

class ChangeColorTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_change_colors()
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

    public function test_it_can_change_multiple_attributes_at_once()
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

        $c = Color::fromRgb(10, 20, 30);

        $t = new ChangeColor(['red' => 100, 'green' => 200]);
        $this->assertEquals([100, 200, 30], $t->transform($c)->toArray());

        $t = new ChangeColor(['red' => 100, 'green' => 200, 'alpha' => 0.9]);
        $this->assertEquals([100, 200, 30, 0.9], $t->transform($c)->toArray());
    }
}
