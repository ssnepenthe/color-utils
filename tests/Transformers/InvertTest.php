<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Invert;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class InvertTest extends TestCase
{
    protected $t;

    function setUp()
    {
        $this->t = new Invert;
    }

    /** @test */
    function it_can_invert_colors()
    {
        $c = ColorFactory::fromString('#edc');

        // assert_equal("#112233", evaluate("invert(#edc)"))
        $this->assertEquals(
            '#112233',
            $this->t->transform($c)->toHexString()
        );

        $c = ColorFactory::fromRgba(10, 20, 30, 0.5);

        // assert_equal("rgba(245, 235, 225, 0.5)", evaluate("invert(rgba(10, 20, 30, 0.5))"))
        $this->assertEquals('rgba(245, 235, 225, 0.5)', $this->t->transform($c));
    }
}
