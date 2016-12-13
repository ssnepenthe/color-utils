<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Invert;

class InvertTest extends TransformerTestCase
{
    public function test_it_can_invert_hex_colors()
    {
        $color = Color::fromString('#edc');

        $tests = [
            // assert_equal("#112233", evaluate("invert(#edc)"))
            ['transformer' => new Invert, 'result' => [17, 34, 51]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_invert_rgb_colors()
    {
        $color = Color::fromRgb(10, 20, 30, 0.5);

        $tests = [
            // assert_equal("rgba(245, 235, 225, 0.5)", evaluate("invert(rgba(10, 20, 30, 0.5))"))
            ['transformer' => new Invert, 'result' => [245, 235, 225, 0.5]]
        ];

        $this->runTransformerTests($color, $tests);
    }
}

