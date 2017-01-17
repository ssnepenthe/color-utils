<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Invert;

class InvertTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new Invert;
    }

    public function test_it_can_invert_colors()
    {
        $c = Color::fromString('#edc');

        // assert_equal("#112233", evaluate("invert(#edc)"))
        $this->assertEquals(
            '#112233',
            $this->t->transform($c)->getRgb()->toHexString()
        );

        $c = Color::fromRgb(10, 20, 30, 0.5);

        // assert_equal("rgba(245, 235, 225, 0.5)", evaluate("invert(rgba(10, 20, 30, 0.5))"))
        $this->assertEquals('rgba(245, 235, 225, 0.5)', $this->t->transform($c));
    }
}
