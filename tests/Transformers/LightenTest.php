<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class LightenTest extends TestCase
{
    /** @test */
    function it_can_lighten_colors()
    {
        $c = ColorFactory::fromString('#800');

        // assert_equal("#ee0000", evaluate("lighten(#800, 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('#ee0000', $t->transform($c)->toHexString());

        // assert_equal("white", evaluate("lighten(#800, 100%)"))
        $t = new Lighten(100);
        $this->assertEquals('white', $t->transform($c)->getName());

        $c = ColorFactory::fromHsl(0, 0, 0);

        // assert_equal("#4d4d4d", evaluate("lighten(hsl(0, 0, 0), 30%)"))
        $t = new Lighten(30);
        $this->assertEquals('#4d4d4d', $t->transform($c)->toHexString());

        $c = ColorFactory::fromRgba(136, 0, 0, 0.5);

        // assert_equal("rgba(238, 0, 0, 0.5)", evaluate("lighten(rgba(136, 0, 0, 0.5), 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('rgba(238, 0, 0, 0.5)', $t->transform($c));
    }

    /** @test */
    function it_can_only_go_as_light_as_white()
    {
        $c = ColorFactory::fromString('white');

        // assert_equal("white", evaluate("lighten(#fff, 20%)"))
        $t = new Lighten(20);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    /** @test */
    function it_throws_when_given_invalid_adjustments()
    {
        // SASS allows this, I don't like it.
        $this->expectException(InvalidArgumentException::class);

        // assert_equal("#880000", evaluate("lighten(#800, 0%)"))
        $t = new Lighten(0);
    }
}
