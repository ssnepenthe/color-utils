<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Complement;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class ComplementTest extends TestCase
{
    protected $t;

    function setUp()
    {
        $this->t = new Complement;
    }

    /** @test */
    function it_can_create_complements_of_colors()
    {
        // assert_equal("#ccbbaa", evaluate("complement(#abc)"))
        $c = ColorFactory::fromString('#abc');
        $this->assertEquals(
            '#ccbbaa',
            $this->t->transform($c)->toHexString()
        );

        // assert_equal("cyan", evaluate("complement(red)"))
        // SASS uses cyan but underneath it is just an alias for aqua.
        $c = ColorFactory::fromString('red');
        $this->assertEquals('aqua', $this->t->transform($c)->getName());

        // assert_equal("red", evaluate("complement(cyan)"))
        $c =  ColorFactory::fromString('aqua');
        $this->assertEquals('red', $this->t->transform($c)->getName());
    }

    /** @test */
    function it_cant_adjust_shades_of_gray()
    {
        // assert_equal("white", evaluate("complement(white)"))
        $c = ColorFactory::fromString('white');
        $this->assertEquals('white', $this->t->transform($c)->getName());

        // assert_equal("black", evaluate("complement(black)"))
        $c = ColorFactory::fromString('black');
        $this->assertEquals('black', $this->t->transform($c)->getName());
    }
}
