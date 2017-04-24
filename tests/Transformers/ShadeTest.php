<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Transformers\Shade;
use SSNepenthe\ColorUtils\Colors\ColorFactory;

/**
 * Tests duplicated from Bourbon.
 *
 * @link https://github.com/thoughtbot/bourbon/blob/master/spec/bourbon/library/shade_spec.rb
 */
class ShadeTest extends TestCase
{
    /** @test */
    function shading_white_gives_gray()
    {
        $c = ColorFactory::fromString('#fff');

        // .shade-white {
        //   color: shade(#fff, 75%); // #404040
        // }
        $t = new Shade(75);
        $this->assertEquals('#404040', $t->transform($c)->toHexString());
    }

    /** @test */
    function shading_black_gives_black()
    {
        $c = ColorFactory::fromString('#000');

        // .shade-black {
        //   color: shade(#000, 50%); // black
        // }
        // Also tests that 50 is default amount.
        foreach ([new Shade(50), new Shade] as $t) {
            $this->assertEquals('black', $t->transform($c)->getName());
        }
    }

    /** @test */
    function it_can_shade_colors()
    {
        // .shade-red {
        //   color: shade(#f00, 25%); // #bf0000
        // }
        $c = ColorFactory::fromString('#f00');
        $t = new Shade(25);
        $this->assertEquals('#bf0000', $t->transform($c)->toHexString());

        // .shade-gray {
        //   color: shade(#222, 33%); // #171717
        // }
        $c = ColorFactory::fromString('#222');
        $t = new Shade(33);
        $this->assertEquals('#171717', $t->transform($c)->toHexString());
    }
}
