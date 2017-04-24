<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Transparentize;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class TransparentizeTest extends TestCase
{
    /** @test */
    function it_can_add_transparency_to_colors()
    {
        $t = new Transparentize(0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("transparentize(rgba(0, 0, 0, 0.5), 0.2)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0.3)',
            $t->transform(ColorFactory::fromRgba(0, 0, 0, 0.5))
        );

        // Since keywords/names are mapped to hex strings and we don't allow RRGGBBAA
        // hex strings, it is impossible to recognize "transparent" as a color name.
        // assert_equal("transparent", evaluate("fade_out(rgba(0, 0, 0, 0.2), 0.2)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0)',
            $t->transform(ColorFactory::fromRgba(0, 0, 0, 0.2))
        );

        $t = new Transparentize(0.1);

        // assert_equal("rgba(0, 0, 0, 0.1)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0.1)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0.1)',
            $t->transform(ColorFactory::fromRgba(0, 0, 0, 0.2))
        );

        $t = new Transparentize(1);

        // See note about "transparent" color name above.
        // assert_equal("transparent", evaluate("transparentize(rgba(0, 0, 0, 0.2), 1)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0)',
            $t->transform(ColorFactory::fromRgba(0, 0, 0, 1))
        );
    }

    /** @test */
    function it_throws_when_invalid_adjustments_provided()
    {
        // SASS allows this, I don't like it.
        $this->expectException(InvalidArgumentException::class);

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0)"))
        $t = new Transparentize(0);
    }
}
