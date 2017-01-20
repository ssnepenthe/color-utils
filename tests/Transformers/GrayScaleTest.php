<?php

use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\GrayScale;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class GrayScaleTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new GrayScale;
    }

    public function test_it_can_convert_colors_to_grayscale()
    {
        $c = ColorFactory::fromString('#abc');

        // assert_equal("#bbbbbb", evaluate("grayscale(#abc)"))
        $this->assertEquals(
            '#bbbbbb',
            $this->t->transform($c)->toHexString()
        );

        $c = ColorFactory::fromHsl(25, 100, 80);

        $this->assertEquals('hsl(25, 0%, 80%)', $this->t->transform($c));

        $c = ColorFactory::fromRgb(136, 0, 0);

        $this->assertEquals('rgb(68, 68, 68)', $this->t->transform($c));
    }

    public function test_it_doesnt_change_shades_of_gray()
    {
        $c = ColorFactory::fromString('#fff');

        // assert_equal("white", evaluate("grayscale(white)"))
        $this->assertEquals('white', $this->t->transform($c)->getName());

        $c = ColorFactory::fromString('#000');

        // assert_equal("black", evaluate("grayscale(black)"))
        $this->assertEquals('black', $this->t->transform($c)->getName());
    }

    public function test_it_converts_primary_colors_straight_to_gray()
    {
        $c = ColorFactory::fromString('#f00');

        // assert_equal("gray", evaluate("grayscale(#f00)"))
        $this->assertEquals('gray', $this->t->transform($c)->getName());

        $c = ColorFactory::fromString('#00f');

        // assert_equal("gray", evaluate("grayscale(#00f)"))
        $this->assertEquals('gray', $this->t->transform($c)->getName());
    }
}
