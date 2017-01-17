<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Mix;

class MixTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_mix_colors()
    {
        // assert_equal("purple", evaluate("mix(#f00, #00f)"))
        $t = new Mix(Color::fromString('#f00'));
        $this->assertEquals(
            'purple',
            $t->transform(Color::fromString('#00f'))->getName()
        );

        // assert_equal("gray", evaluate("mix(#f00, #0ff)"))
        $t = new Mix(Color::fromString('#f00'));
        $this->assertEquals(
            'gray',
            $t->transform(Color::fromString('#0ff'))->getName()
        );

        // assert_equal("#4000bf", evaluate("mix(#f00, #00f, 25%)"))
        $t = new Mix(Color::fromString('#f00'), 25);
        $this->assertEquals(
            '#4000bf',
            $t->transform(Color::fromString('#00f'))->getRgb()->toHexString()
        );

        // assert_equal("red", evaluate("mix(#f00, #00f, 100%)"))
        $t = new Mix(Color::fromString('#f00'), 100);
        $this->assertEquals(
            'red',
            $t->transform(Color::fromString('#00f'))->getName()
        );

        // assert_equal("blue", evaluate("mix(#f00, #00f, 0%)"))
        $t = new Mix(Color::fromString('#f00'), 0);
        $this->assertEquals(
            'blue',
            $t->transform(Color::fromString('#00f'))->getName()
        );

        // assert_equal("#809155", evaluate("mix(#f70, #0aa)"))
        $t = new Mix(Color::fromString('#f70'));
        $this->assertEquals(
            '#809155',
            $t->transform(Color::fromString('#0aa'))->getRgb()->toHexString()
        );
    }

    public function test_it_can_mix_colors_with_alpha()
    {
        // assert_equal("rgba(64, 0, 191, 0.75)", evaluate("mix(rgba(255, 0, 0, 0.5), #00f)"))
        $t = new Mix(Color::fromRgb(255, 0, 0, 0.5));
        $this->assertEquals(
            'rgba(64, 0, 191, 0.75)',
            $t->transform(Color::fromString('#00f'))
        );

        // The transparentize() calls are removed from the following tests and
        // replaced with manual alpha adjustments.

        // assert_equal("rgba(255, 0, 0, 0.5)", evaluate("mix(#f00, transparentize(#00f, 1))"))
        $t = new Mix(Color::fromString('#f00'));
        $this->assertEquals(
            'rgba(255, 0, 0, 0.5)',
            $t->transform(Color::fromString('#00f')->with(['alpha' => 0]))
        );

        // assert_equal("rgba(0, 0, 255, 0.5)", evaluate("mix(transparentize(#f00, 1), #00f)"))
        $t = new Mix(Color::fromString('#f00')->with(['alpha' => 0]));
        $this->assertEquals(
            'rgba(0, 0, 255, 0.5)',
            $t->transform(Color::fromString('#00f'))
        );

        // assert_equal("red", evaluate("mix(#f00, transparentize(#00f, 1), 100%)"))
        $t = new Mix(Color::fromString('#f00'), 100);
        $this->assertEquals(
            'red',
            $t->transform(Color::fromString('#00f')->with(['alpha' => 0]))->getName()
        );

        // assert_equal("blue", evaluate("mix(transparentize(#f00, 1), #00f, 0%)"))
        $t = new Mix(Color::fromString('#f00')->with(['alpha' => 0]), 0);
        $this->assertEquals(
            'blue',
            $t->transform(Color::fromString('#00f'))->getName()
        );

        // assert_equal("rgba(0, 0, 255, 0)", evaluate("mix(#f00, transparentize(#00f, 1), 0%)"))
        $t = new Mix(Color::fromString('#f00'), 0);
        $this->assertEquals(
            'rgba(0, 0, 255, 0)',
            $t->transform(Color::fromString('#00f')->with(['alpha' => 0]))
        );

        // assert_equal("rgba(255, 0, 0, 0)", evaluate("mix(transparentize(#f00, 1), #00f, 100%)"))
        // assert_equal("rgba(255, 0, 0, 0)", evaluate("mix($color1: transparentize(#f00, 1), $color2: #00f, $weight: 100%)"))
        $t = new Mix(Color::fromString('#f00')->with(['alpha' => 0]), 100);
        $this->assertEquals(
            'rgba(255, 0, 0, 0)',
            $t->transform(Color::fromString('#00f'))
        );

    }
}
