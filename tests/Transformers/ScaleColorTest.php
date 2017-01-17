<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\ScaleColor;

class ScaleColorTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_scale_colors()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(120, 51, 90)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%)"))
        $t = new ScaleColor(['saturation' => 30]);
        $this->assertEquals('hsl(120, 51%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 30, 76.5)"),
        // evaluate("scale-color(hsl(120, 30, 90), $lightness: -15%)"))
        $t = new ScaleColor(['lightness' => -15]);
        $this->assertEquals('hsl(120, 30%, 76.5%)', $t->transform($c));

        $c = Color::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgb(157, 20, 30)"),
        // evaluate("scale-color(rgb(10, 20, 30), $red: 60%)"))
        $t = new ScaleColor(['red' => 60]);
        $this->assertEquals('rgb(157, 20, 30)', $t->transform($c));

        // @todo SASS tests to 38.8 green but the actual SASS output is 39.
        // assert_equal(evaluate("rgb(10, 38.8, 30)"),
        // evaluate("scale-color(rgb(10, 20, 30), $green: 8%)"))
        $t = new ScaleColor(['green' => 8]);
        $this->assertEquals('rgb(10, 39, 30)', $t->transform($c));

        // assert_equal(evaluate("rgb(10, 20, 20)"),
        // evaluate("scale-color(rgb(10, 20, 30), $blue: -(1/3)*100%)"))
        $t = new ScaleColor(['blue' => -(1 / 3) * 100]);
        $this->assertEquals('rgb(10, 20, 20)', $t->transform($c));

        // assert_equal(evaluate("hsla(120, 30, 90, 0.86)"),
        // evaluate("scale-color(hsl(120, 30, 90), $alpha: -14%)"))
        $t = new ScaleColor(['alpha' => -14]);
        $this->assertEquals(
            'hsla(120, 30%, 90%, 0.86)',
            $t->transform(Color::fromHsl(120, 30, 90))
        );

        // assert_equal(evaluate("rgba(10, 20, 30, 0.82)"),
        // evaluate("scale-color(rgba(10, 20, 30, 0.8), $alpha: 10%)"))
        $t = new ScaleColor(['alpha' => 10]);
        $this->assertEquals(
            'rgba(10, 20, 30, 0.82)',
            $t->transform(Color::fromRgb(10, 20, 30, 0.8))
        );
    }

    public function test_it_can_scale_multiple_attributes()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(120, 51, 76.5)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%, $lightness: -15%)"))
        $t = new ScaleColor(['saturation' => 30, 'lightness' => -15]);
        $this->assertEquals('hsl(120, 51%, 76.5%)', $t->transform($c));

        // assert_equal(evaluate("hsla(120, 51, 90, 0.2)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: 30%, $alpha: -80%)"))
        $t = new ScaleColor(['saturation' => 30, 'alpha' => -80]);
        $this->assertEquals('hsla(120, 51%, 90%, 0.2)', $t->transform($c));

        $c = Color::fromRgb(10, 20, 30);

        // @todo While SASS tests to 38.8 green, actual SASS output is 39.
        // assert_equal(evaluate("rgb(157, 38.8, 30)"),
        // evaluate("scale-color(rgb(10, 20, 30), $red: 60%, $green: 8%)"))
        $t = new ScaleColor(['red' => 60, 'green' => 8]);
        $this->assertEquals('rgb(157, 39, 30)', $t->transform($c));

        // @todo See note above.
        // assert_equal(evaluate("rgb(157, 38.8, 20)"),
        // evaluate("scale-color(rgb(10, 20, 30), $red: 60%, $green: 8%, $blue: -(1/3)*100%)"))
        $t = new ScaleColor(['red' => 60, 'green' => 8, 'blue' => -(1 / 3) * 100]);
        $this->assertEquals('rgb(157, 39, 20)', $t->transform($c));

        $c = Color::fromRgb(10, 20, 30, 0.5);

        // @todo See note above.
        // assert_equal(evaluate("rgba(10, 38.8, 20, 0.55)"),
        // evaluate("scale-color(rgba(10, 20, 30, 0.5), $green: 8%, $blue: -(1/3)*100%, $alpha: 10%)"))
        $t = new ScaleColor(['green' => 8, 'blue' => -(1 / 3) * 100, 'alpha' => 10]);
        $this->assertEquals('rgba(10, 39, 20, 0.55)', $t->transform($c));
    }

    public function test_it_can_handle_extremes()
    {
        $c = Color::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(120, 100, 90)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: 100%)"))
        $t = new ScaleColor(['saturation' => 100]);
        $this->assertEquals('hsl(120, 100%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 30, 90)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: 0%)"))
        $t = new ScaleColor(['saturation' => 0]);
        $this->assertEquals('hsl(120, 30%, 90%)', $t->transform($c));

        // assert_equal(evaluate("hsl(120, 0, 90)"),
        // evaluate("scale-color(hsl(120, 30, 90), $saturation: -100%)"))
        $t = new ScaleColor(['saturation' => -100]);
        $this->assertEquals('hsl(120, 0%, 90%)', $t->transform($c));
    }
}
