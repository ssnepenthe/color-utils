<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Shade;

class ShadeTest extends TransformerTestCase
{
    public function test_shading_white_gives_gray()
    {
        $color = Color::fromHex('#fff');

        $tests = [
            // .shade-white {
            //   color: shade(#fff, 75%); // #404040
            // }
            ['transformer' => new Shade(75), 'result' => [64, 64, 64]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_shading_black_just_gives_black()
    {
        $color = Color::fromHex('#000');

        $tests = [
            // .shade-black {
            //   color: shade(#000, 50%); // black
            // }
            ['transformer' => new Shade(50), 'result' => [0, 0, 0]],
            // Test that 50 is default Shade amount.
            ['transformer' => new Shade, 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_shade_red()
    {
        $color = Color::fromHex('#f00');

        $tests = [
            // .shade-red {
            //   color: shade(#f00, 25%); // #bf0000
            // }
            ['transformer' => new Shade(25), 'result' => [191, 0, 0]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_shade_grays()
    {
        $color = Color::fromHex('#222');

        $tests = [
            // .shade-gray {
            //   color: shade(#222, 33%); // #171717
            // }
            ['transformer' => new Shade(33), 'result' => [23, 23, 23]],
        ];

        $this->runTransformerTests($color, $tests);
    }
}
