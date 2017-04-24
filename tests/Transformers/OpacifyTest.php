<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Opacify;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class OpacifyTest extends TestCase
{
    /** @test */
    function it_can_add_opacity_to_colors()
    {
        $c = ColorFactory::fromRgba(0, 0, 0, 0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0.1)"))
        $t = new Opacify(0.1);
        $this->assertEquals('rgba(0, 0, 0, 0.3)', $t->transform($c));

        // assert_equal("black", evaluate("fade_in(rgba(0, 0, 0, 0.2), 0.8)"))
        $t = new Opacify(0.8);
        $this->assertEquals('black', $t->transform($c)->getName());

        // assert_equal("black", evaluate("opacify(rgba(0, 0, 0, 0.2), 1)"))
        $t = new Opacify(1.0);
        $this->assertEquals('black', $t->transform($c)->getName());

        $c = ColorFactory::fromRgba(0, 0, 0, 0.5);
        $t = new Opacify(0.25);

        // assert_equal("rgba(0, 0, 0, 0.75)", evaluate("opacify(rgba(0, 0, 0, 0.5), 0.25)"))
        $this->assertEquals('rgba(0, 0, 0, 0.75)', $t->transform($c));
    }

    /** @test */
    function it_throws_when_given_invalid_adjustments()
    {
        // SASS allows this, I don't like it.
        $this->expectException(InvalidArgumentException::class);

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0%)"))
        $t = new Opacify(0.0);
    }
}
