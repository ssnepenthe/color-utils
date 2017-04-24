<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\AdjustColor;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class AdjustColorTest extends TestCase
{
    /** @test */
    function it_can_adjust_colors()
    {
        $c = ColorFactory::fromHsl(120, 30, 90);

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

        $c = ColorFactory::fromRgb(10, 20, 30);

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

    /** @test */
    function it_can_adjust_alpha_values()
    {
        // assert_equal(evaluate("hsla(120, 30, 90, 0.65)"),
        // evaluate("adjust-color(hsl(120, 30, 90), $alpha: -0.35)"))
        $c = ColorFactory::fromHsl(120, 30, 90);
        $t = new AdjustColor(['alpha' => -0.35]);
        $this->assertEquals(
            'hsla(120, 30%, 90%, 0.65)',
            $t->transform($c)
        );

        // assert_equal(evaluate("rgba(10, 20, 30, 0.9)"),
        // evaluate("adjust-color(rgba(10, 20, 30, 0.4), $alpha: 0.5)"))
        $c = ColorFactory::fromRgba(10, 20, 30, 0.4);
        $t = new AdjustColor(['alpha' => 0.5]);
        $this->assertEquals('rgba(10, 20, 30, 0.9)', $t->transform($c));
    }

    /** @test */
    function it_can_adjust_multiple_attributes_at_once()
    {
        $c = ColorFactory::fromHsl(120, 30, 90);

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

        $c = ColorFactory::fromRgb(10, 20, 30);

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

    /** @test */
    function it_honors_range_restrictions()
    {
        // Technically restrictions are handled in the Hsl and Rgb classes...
        $c = ColorFactory::fromHsl(120, 30, 90);

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

        $c = ColorFactory::fromRgb(10, 20, 30);

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

    /** @test */
    function it_throws_when_given_non_numeric_adjustments()
    {
        $this->expectException(InvalidArgumentException::class);

        $t = new AdjustColor(['blue' => 'test']);
    }

    /** @test */
    function it_throws_when_given_adjustments_of_zero()
    {
        $this->expectException(InvalidArgumentException::class);

        $t = new AdjustColor(['green' => 0]);
    }

    /** @test */
    function it_discards_invalid_channels()
    {
        // Basically testing that no BadMethodCallException is thrown.
        $t = new AdjustColor(['purple' => 50, 'blue' => 25]);

        $this->assertEquals(
            'rgb(255, 0, 25)',
            $t->transform(ColorFactory::fromString('red'))
        );
    }
}
