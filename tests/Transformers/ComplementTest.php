<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Complement;

class ComplementTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new Complement;
    }

    public function test_it_can_create_complements_of_colors()
    {
        // assert_equal("#ccbbaa", evaluate("complement(#abc)"))
        $c = Color::fromString('#abc');
        $this->assertEquals(
            '#ccbbaa',
            $this->t->transform($c)->getRgb()->toHexString()
        );

        // assert_equal("cyan", evaluate("complement(red)"))
        // SASS uses cyan but underneath it is just an alias for aqua.
        $c = Color::fromString('red');
        $this->assertEquals('aqua', $this->t->transform($c)->getName());

        // assert_equal("red", evaluate("complement(cyan)"))
        $c =  Color::fromString('aqua');
        $this->assertEquals('red', $this->t->transform($c)->getName());
    }

    public function test_it_cant_adjust_shades_of_gray()
    {
        // assert_equal("white", evaluate("complement(white)"))
        $c = Color::fromString('white');
        $this->assertEquals('white', $this->t->transform($c)->getName());

        // assert_equal("black", evaluate("complement(black)"))
        $c = Color::fromString('black');
        $this->assertEquals('black', $this->t->transform($c)->getName());
    }
}
