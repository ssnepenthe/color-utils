<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Mix;

/**
 * @todo More tests once transparentize has been implemented:
 *
 * assert_equal("rgba(255, 0, 0, 0.5)", evaluate("mix(#f00, transparentize(#00f, 1))"))
 * assert_equal("rgba(0, 0, 255, 0.5)", evaluate("mix(transparentize(#f00, 1), #00f)"))
 * assert_equal("red", evaluate("mix(#f00, transparentize(#00f, 1), 100%)"))
 * assert_equal("blue", evaluate("mix(transparentize(#f00, 1), #00f, 0%)"))
 * assert_equal("rgba(0, 0, 255, 0)", evaluate("mix(#f00, transparentize(#00f, 1), 0%)"))
 * assert_equal("rgba(255, 0, 0, 0)", evaluate("mix(transparentize(#f00, 1), #00f, 100%)"))
 * assert_equal("rgba(255, 0, 0, 0)", evaluate("mix($color1: transparentize(#f00, 1), $color2: #00f, $weight: 100%)"))
 */
class MixTest extends TransformerTestCase
{
    /**
     * Weights are reversed from SASS tests so they can share the base color.
     */
    public function test_it_can_mix_red_with_other_colors()
    {
        $color = Color::fromString('#f00');

        $tests = [
            // assert_equal("purple", evaluate("mix(#f00, #00f)"))
            [
                'transformer' => new Mix(Color::fromString('#00f')),
                'result' => [128, 0, 128]
            ],
            // assert_equal("gray", evaluate("mix(#f00, #0ff)"))
            [
                'transformer' => new Mix(Color::fromString('#0ff')),
                'result' => [128, 128, 128]
            ],
            // assert_equal("#4000bf", evaluate("mix(#f00, #00f, 25%)"))
            [
                'transformer' => new Mix(Color::fromString('#00f'), 75),
                'result' => [64, 0, 191]
            ],
            // assert_equal("red", evaluate("mix(#f00, #00f, 100%)"))
            [
                'transformer' => new Mix(Color::fromString('#00f'), 0),
                'result' => [255, 0, 0]
            ],
            // assert_equal("blue", evaluate("mix(#f00, #00f, 0%)"))
            [
                'transformer' => new Mix(Color::fromString('#00f'), 100),
                'result' => [0, 0, 255]
            ],
        ];

        $this->runTransformerTests($color, $tests);
    }


    public function test_it_can_mix_random_blue_with_other_colors()
    {
        // assert_equal("#809155", evaluate("mix(#f70, #0aa)"))
        $transformer = new Mix(Color::fromString('#f70'));

        $this->assertEquals(
            [128, 145, 85],
            $transformer->transform(Color::fromString('#0aa'))->toArray()
        );
    }

    public function test_it_can_mix_transparent_red_with_blue()
    {
        // assert_equal("rgba(64, 0, 191, 0.75)", evaluate("mix(rgba(255, 0, 0, 0.5), #00f)"))
        $transformer = new Mix(Color::fromRgb(255, 0, 0, 0.5));

        $this->assertEquals(
            [64, 0, 191, 0.75],
            $transformer->transform(Color::fromString('#00f'))->toArray()
        );
    }
}
