<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Desaturate;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class DesaturateTest extends TestCase
{
    /** @test */
    function it_can_desaturate_colors()
    {
        $c = ColorFactory::fromHsl(120, 30, 90);

        // assert_equal("#e3e8e3", evaluate("desaturate(hsl(120, 30, 90), 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('#e3e8e3', $t->transform($c)->toHexString());

        $c = ColorFactory::fromString('#855');

        // assert_equal("#726b6b", evaluate("desaturate(#855, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('#726b6b', $t->transform($c)->toHexString());

        $c = ColorFactory::fromRgba(136, 85, 85, 0.5);

        // assert_equal("rgba(114, 107, 107, 0.5)", evaluate("desaturate(rgba(136, 85, 85, 0.5), 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('rgba(114, 107, 107, 0.5)', $t->transform($c));
    }

    /** @test */
    function it_cant_desaturate_shades_of_gray()
    {
        $c = ColorFactory::fromString('#000');

        // assert_equal("black", evaluate("desaturate(#000, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('black', $t->transform($c)->getName());

        $c = ColorFactory::fromString('#fff');

        // assert_equal("white", evaluate("desaturate(#fff, 20%)"))
        $t = new Desaturate(20);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    /** @test */
    function it_handles_the_extremes()
    {
        $c = ColorFactory::fromString('#8a8');

        // assert_equal("#999999", evaluate("desaturate(#8a8, 100%)"))
        $t = new Desaturate(100);
        $this->assertEquals('#999999', $t->transform($c)->toHexString());
    }

    /** @test */
    function it_throws_when_given_invalid_adjustments()
    {
        // SASS allows this, I don't like it.
        $this->expectException(InvalidArgumentException::class);

        // assert_equal("#88aa88", evaluate("desaturate(#8a8, 0%)"))
        $t = new Desaturate(0);
    }
}
