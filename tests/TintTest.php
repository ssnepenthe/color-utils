<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Tint;

class TintTest extends TransformerTestCase
{
    public function test_tinting_white_just_gives_white()
    {
        $color = Color::fromHex('#fff');

        $tests = [
            // .tint-white {
            //   color: tint(#fff, 75%); // white
            // }
            ['transformer' => new Tint(75), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_tinting_black_gives_gray()
    {
        $color = Color::fromHex('#000');

        $tests = [
            // .tint-black {
            //   color: tint(#000, 50%); // gray
            // }
            ['transformer' => new Tint(50), 'result' => [128, 128, 128]],
            // Test that 50 is default Tint amount.
            ['transformer' => new Tint, 'result' => [128, 128, 128]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_tint_red()
    {
        $color = Color::fromHex('#f00');

        $tests = [
            // .tint-red {
            //   color: tint(#f00, 25%); // #ff4040
            // }
            ['transformer' => new Tint(25), 'result' => [255, 64, 64]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_tint_grays()
    {
        $color = Color::fromHex('#aaa');

        $tests = [
            // .tint-gray {
            //   color: tint(#aaa, 33%); // #c6c6c6
            // }
            ['transformer' => new Tint(33), 'result' => [198, 198, 198]],
        ];

        $this->runTransformerTests($color, $tests);
    }
}

