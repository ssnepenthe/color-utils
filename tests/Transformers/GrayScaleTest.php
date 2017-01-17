<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\GrayScale;

class GrayScaleTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new GrayScale;
    }

    public function test_it_can_convert_colors_to_grayscale()
    {
        $c = Color::fromString('#abc');

        // assert_equal("#bbbbbb", evaluate("grayscale(#abc)"))
        $this->assertEquals(
            '#bbbbbb',
            $this->t->transform($c)->getRgb()->toHexString()
        );

        $c = Color::fromHsl(25, 100, 80);

        $this->assertEquals([25, 0, 80], $this->t->transform($c)->toArray());

        $c = Color::fromRgb(136, 0, 0);

        $this->assertEquals([68, 68, 68], $this->t->transform($c)->toArray());
    }

    public function test_it_doesnt_change_shades_of_gray()
    {
        $c = Color::fromString('#fff');

        // assert_equal("white", evaluate("grayscale(white)"))
        $this->assertEquals('white', $this->t->transform($c)->getName());

        $c = Color::fromString('#000');

        // assert_equal("black", evaluate("grayscale(black)"))
        $this->assertEquals('black', $this->t->transform($c)->getName());
    }

    public function test_it_converts_primary_colors_straight_to_gray()
    {
        $c = Color::fromString('#f00');

        // assert_equal("gray", evaluate("grayscale(#f00)"))
        $this->assertEquals('gray', $this->t->transform($c)->getName());

        $c = Color::fromString('#00f');

        // assert_equal("gray", evaluate("grayscale(#00f)"))
        $this->assertEquals('gray', $this->t->transform($c)->getName());
    }
}
