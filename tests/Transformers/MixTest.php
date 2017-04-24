<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Transformers\Mix;
use SSNepenthe\ColorUtils\Colors\ColorFactory;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class MixTest extends TestCase
{
    /** @test */
    function it_can_mix_colors()
    {
        // assert_equal("purple", evaluate("mix(#f00, #00f)"))
        $t = new Mix(ColorFactory::fromString('#f00'));
        $this->assertEquals(
            'purple',
            $t->transform(ColorFactory::fromString('#00f'))->getName()
        );

        // assert_equal("gray", evaluate("mix(#f00, #0ff)"))
        $t = new Mix(ColorFactory::fromString('#f00'));
        $this->assertEquals(
            'gray',
            $t->transform(ColorFactory::fromString('#0ff'))->getName()
        );

        // assert_equal("#4000bf", evaluate("mix(#f00, #00f, 25%)"))
        $t = new Mix(ColorFactory::fromString('#f00'), 25);
        $this->assertEquals(
            '#4000bf',
            $t->transform(ColorFactory::fromString('#00f'))->toHexString()
        );

        // assert_equal("red", evaluate("mix(#f00, #00f, 100%)"))
        $t = new Mix(ColorFactory::fromString('#f00'), 100);
        $this->assertEquals(
            'red',
            $t->transform(ColorFactory::fromString('#00f'))->getName()
        );

        // assert_equal("blue", evaluate("mix(#f00, #00f, 0%)"))
        $t = new Mix(ColorFactory::fromString('#f00'), 0);
        $this->assertEquals(
            'blue',
            $t->transform(ColorFactory::fromString('#00f'))->getName()
        );

        // assert_equal("#809155", evaluate("mix(#f70, #0aa)"))
        $t = new Mix(ColorFactory::fromString('#f70'));
        $this->assertEquals(
            '#809155',
            $t->transform(ColorFactory::fromString('#0aa'))->toHexString()
        );
    }

    /** @test */
    function it_can_mix_colors_with_alpha()
    {
        // assert_equal("rgba(64, 0, 191, 0.75)", evaluate("mix(rgba(255, 0, 0, 0.5), #00f)"))
        $t = new Mix(ColorFactory::fromRgba(255, 0, 0, 0.5));
        $this->assertEquals(
            'rgba(64, 0, 191, 0.75)',
            $t->transform(ColorFactory::fromString('#00f'))
        );

        // The transparentize() calls are removed from the following tests and
        // replaced with manual alpha adjustments.

        // assert_equal("rgba(255, 0, 0, 0.5)", evaluate("mix(#f00, transparentize(#00f, 1))"))
        $t = new Mix(ColorFactory::fromString('#f00'));
        $this->assertEquals(
            'rgba(255, 0, 0, 0.5)',
            $t->transform(ColorFactory::fromString('#00f')->with(['alpha' => 0]))
        );

        // assert_equal("rgba(0, 0, 255, 0.5)", evaluate("mix(transparentize(#f00, 1), #00f)"))
        $t = new Mix(ColorFactory::fromString('#f00')->with(['alpha' => 0]));
        $this->assertEquals(
            'rgba(0, 0, 255, 0.5)',
            $t->transform(ColorFactory::fromString('#00f'))
        );

        // assert_equal("red", evaluate("mix(#f00, transparentize(#00f, 1), 100%)"))
        $t = new Mix(ColorFactory::fromString('#f00'), 100);
        $this->assertEquals(
            'red',
            $t->transform(
                ColorFactory::fromString('#00f')->with(['alpha' => 0])
            )->getName()
        );

        // assert_equal("blue", evaluate("mix(transparentize(#f00, 1), #00f, 0%)"))
        $t = new Mix(ColorFactory::fromString('#f00')->with(['alpha' => 0]), 0);
        $this->assertEquals(
            'blue',
            $t->transform(ColorFactory::fromString('#00f'))->getName()
        );

        // assert_equal("rgba(0, 0, 255, 0)", evaluate("mix(#f00, transparentize(#00f, 1), 0%)"))
        $t = new Mix(ColorFactory::fromString('#f00'), 0);
        $this->assertEquals(
            'rgba(0, 0, 255, 0)',
            $t->transform(ColorFactory::fromString('#00f')->with(['alpha' => 0]))
        );

        // assert_equal("rgba(255, 0, 0, 0)", evaluate("mix(transparentize(#f00, 1), #00f, 100%)"))
        // assert_equal("rgba(255, 0, 0, 0)", evaluate("mix($color1: transparentize(#f00, 1), $color2: #00f, $weight: 100%)"))
        $t = new Mix(ColorFactory::fromString('#f00')->with(['alpha' => 0]), 100);
        $this->assertEquals(
            'rgba(255, 0, 0, 0)',
            $t->transform(ColorFactory::fromString('#00f'))
        );

    }
}
