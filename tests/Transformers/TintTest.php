<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Tint;

class TintTest extends PHPUnit_Framework_TestCase
{
    public function test_tinting_white_just_gives_white()
    {
        // .tint-white {
        //   color: tint(#fff, 75%); // white
        // }
        $c = Color::fromString('#fff');
        $t = new Tint(75);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    public function test_it_can_tint_colors()
    {
        // .tint-black {
        //   color: tint(#000, 50%); // gray
        // }
        $c = Color::fromString('#000');
        foreach ([new Tint(50), new Tint] as $t) {
            $this->assertEquals('gray', $t->transform($c)->getName());
        }

        $c = Color::fromString('#f00');

        // .tint-red {
        //   color: tint(#f00, 25%); // #ff4040
        // }
        $t = new Tint(25);
        $this->assertEquals('#ff4040', $t->transform($c)->getRgb()->toHexString());

        // .tint-gray {
        //   color: tint(#aaa, 33%); // #c6c6c6
        // }
        $c = Color::fromString('#aaa');
        $t = new Tint(33);
        $this->assertEquals('#c6c6c6', $t->transform($c)->getRgb()->toHexString());
    }
}
