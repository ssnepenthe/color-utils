<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Complement;

class ComplementTest extends TransformerTestCase
{
    public function test_it_can_create_complement_from_hex()
    {
        $color = Color::fromHex('#abc');

        $tests = [
            // @todo Conversion is off by one from SASS for each value.
            // assert_equal("#ccbbaa", evaluate("complement(#abc)"))
            ['transformer' => new Complement, 'result' => [203, 186, 169]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_create_complement_from_keywords()
    {
        $color = Color::fromHex('#f00');

        $tests = [
            // assert_equal("cyan", evaluate("complement(red)"))
            ['transformer' => new Complement, 'result' => [0, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromHex('#0ff');

        $tests = [
            // assert_equal("red", evaluate("complement(cyan)"))
            ['transformer' => new Complement, 'result' => [255, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_cant_adjust_shades_of_gray()
    {
        $color = Color::fromHex('#fff');

        $tests = [
            // assert_equal("white", evaluate("complement(white)"))
            ['transformer' => new Complement, 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromHex('#000');

        $tests = [
            // assert_equal("black", evaluate("complement(black)"))
            ['transformer' => new Complement, 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }
}
