<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\ChangeColor;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class ChangeColorTest extends TestCase
{
    /** @test */
    function it_can_change_colors()
    {
        $c = ColorFactory::fromHsl(120, 30, 90);

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

        $c = ColorFactory::fromRgb(10, 20, 30);

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

    /** @test */
    function it_can_change_alpha_values()
    {
        $c = ColorFactory::fromRgb(10, 20, 30);

        // assert_equal(evaluate("rgba(10, 20, 30, 0.76)"),
        // evaluate("change-color(rgb(10, 20, 30), $alpha: 0.76)"))
        $t = new ChangeColor(['alpha' => 0.76]);
        $this->assertEquals('rgba(10, 20, 30, 0.76)', $t->transform($c));
    }

    /** @test */
    function it_can_change_multiple_attributes_at_once()
    {
        $c = ColorFactory::fromHsl(120, 30, 90);

        // assert_equal(evaluate("hsl(56, 30, 47)"),
        // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%)"))
        $t = new ChangeColor(['hue' => 56, 'lightness' => 47]);
        $this->assertEquals('hsl(56, 30%, 47%)', $t->transform($c));

        // assert_equal(evaluate("hsla(56, 30, 47, 0.9)"),
        // evaluate("change-color(hsl(120, 30, 90), $hue: 56deg, $lightness: 47%, $alpha: 0.9)"))
        $t = new ChangeColor(['hue' => 56, 'lightness' => 47, 'alpha' => 0.9]);
        $this->assertEquals('hsla(56, 30%, 47%, 0.9)', $t->transform($c));

        $c = ColorFactory::fromRgb(10, 20, 30);

        $t = new ChangeColor(['red' => 100, 'green' => 200]);
        $this->assertEquals('rgb(100, 200, 30)', $t->transform($c));

        $t = new ChangeColor(['red' => 100, 'green' => 200, 'alpha' => 0.9]);
        $this->assertEquals('rgba(100, 200, 30, 0.9)', $t->transform($c));
    }

    /** @test */
    function it_throws_when_given_non_numeric_adjustments()
    {
        $this->expectException(InvalidArgumentException::class);

        $t = new ChangeColor(['blue' => 'test']);
    }

    /** @test */
    function it_discards_invalid_channels()
    {
        // Basically testing that no BadMethodCallException is thrown.
        $t = new ChangeColor(['purple' => 50, 'blue' => 25]);

        $this->assertEquals(
            'rgb(255, 0, 25)',
            $t->transform(ColorFactory::fromString('red'))
        );
    }
}
