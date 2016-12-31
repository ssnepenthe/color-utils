<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\complement;
use SSNepenthe\ColorUtils\Transformers\Complement;

class ComplementTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new Complement;
    }

    public function test_it_can_create_complement_from_hex()
    {
        // assert_equal("#ccbbaa", evaluate("complement(#abc)"))
        $c = Color::fromString('#abc');

        /**
         * @todo Conversion is off by one from SASS for each channel.
         *       It does match with tools like rgb.to, www.rapidtables.com, etc.
         */
        $this->assertEquals(
            '#cbbaa9',
            $this->t->transform($c)->getRgb()->toHexString()
        );
    }

    public function test_it_can_create_complement_from_keywords()
    {
        // assert_equal("cyan", evaluate("complement(red)"))
        // SASS uses cyan but underneath it is just an alias for aqua.
        $c = Color::fromString('#f00');
        $this->assertEquals('aqua', $this->t->transform($c)->getName());

        // assert_equal("red", evaluate("complement(cyan)"))
        $c =  Color::fromString('#0ff');
        $this->assertEquals('red', $this->t->transform($c)->getName());
    }

    public function test_it_cant_adjust_shades_of_gray()
    {
        // assert_equal("white", evaluate("complement(white)"))
        $c = Color::fromString('#fff');
        $this->assertEquals('white', $this->t->transform($c)->getName());

        // assert_equal("black", evaluate("complement(black)"))
        $c = Color::fromString('#000');
        $this->assertEquals('black', $this->t->transform($c)->getName());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        foreach ($colors as $c) {
            $this->assertEquals(
                [180, 0, 0],
                $this->t->transform($c)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $this->assertEquals(
            [180, 0, 0],
            complement(Color::fromString('black'))->getHsl()->toArray()
        );
    }
}
