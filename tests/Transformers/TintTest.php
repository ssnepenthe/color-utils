<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Transformers\Tint;
use SSNepenthe\ColorUtils\Colors\ColorFactory;

/**
 * Tests duplicated from Bourbon.
 *
 * @link https://github.com/thoughtbot/bourbon/blob/master/spec/bourbon/library/tint_spec.rb
 */
class TintTest extends TestCase
{
    /** @test */
    function tinting_white_just_gives_white()
    {
        // .tint-white {
        //   color: tint(#fff, 75%); // white
        // }
        $c = ColorFactory::fromString('#fff');
        $t = new Tint(75);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    /** @test */
    function it_can_tint_colors()
    {
        // .tint-black {
        //   color: tint(#000, 50%); // gray
        // }
        $c = ColorFactory::fromString('#000');
        foreach ([new Tint(50), new Tint] as $t) {
            $this->assertEquals('gray', $t->transform($c)->getName());
        }

        $c = ColorFactory::fromString('#f00');

        // .tint-red {
        //   color: tint(#f00, 25%); // #ff4040
        // }
        $t = new Tint(25);
        $this->assertEquals('#ff4040', $t->transform($c)->toHexString());

        // .tint-gray {
        //   color: tint(#aaa, 33%); // #c6c6c6
        // }
        $c = ColorFactory::fromString('#aaa');
        $t = new Tint(33);
        $this->assertEquals('#c6c6c6', $t->transform($c)->toHexString());
    }
}
