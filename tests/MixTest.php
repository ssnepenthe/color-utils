<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\mix;
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
class MixTest extends PHPUnit_Framework_TestCase
{
    /**
     * Weights are reversed from SASS tests so they can share the base color.
     */
    public function test_it_can_mix_red_with_other_colors()
    {
        $c = Color::fromString('#f00');

        // assert_equal("purple", evaluate("mix(#f00, #00f)"))
        $t = new Mix(Color::fromString('#00f'));
        $this->assertEquals('purple', $t->transform($c)->getName());

        // assert_equal("gray", evaluate("mix(#f00, #0ff)"))
        $t = new Mix(Color::fromString('#0ff'));
        $this->assertEquals('gray', $t->transform($c)->getName());

        // assert_equal("#4000bf", evaluate("mix(#f00, #00f, 25%)"))
        $t = new Mix(Color::fromString('#00f'), 75);
        $this->assertEquals('#4000bf', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("red", evaluate("mix(#f00, #00f, 100%)"))
        $t = new Mix(Color::fromString('#00f'), 0);
        $this->assertEquals('red', $t->transform($c)->getName());

        // assert_equal("blue", evaluate("mix(#f00, #00f, 0%)"))
        $t = new Mix(Color::fromString('#00f'), 100);
        $this->assertEquals('blue', $t->transform($c)->getName());
    }


    public function test_it_can_mix_random_blue_with_other_colors()
    {
        // assert_equal("#809155", evaluate("mix(#f70, #0aa)"))
        $t = new Mix(Color::fromString('#f70'));

        $this->assertEquals(
            '#809155',
            $t->transform(Color::fromString('#0aa'))->getRgb()->toHexString()
        );
    }

    public function test_it_can_mix_transparent_red_with_blue()
    {
        // assert_equal("rgba(64, 0, 191, 0.75)", evaluate("mix(rgba(255, 0, 0, 0.5), #00f)"))
        $t = new Mix(Color::fromRgb(255, 0, 0, 0.5));

        $this->assertEquals(
            'rgba(64, 0, 191, 0.75)',
            $t->transform(Color::fromString('#00f'))
        );
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformers = [
            new Mix(Color::fromString('white')),
            new Mix(Rgb::fromString('white')),
            new Mix(Hsl::fromString('hsl(0, 0%, 100%)')),
        ];

        foreach ($colors as $c) {
            foreach ($transformers as $t) {
                $this->assertEquals(
                    [128, 128, 128],
                    $t->transform($c)->getRgb()->toArray()
                );
            }
        }
    }

    public function test_functional_wrapper()
    {
        $color = mix(Color::fromString('#00f'), Color::fromString('#f00'));

        $this->assertEquals([128, 0, 128], $color->getRgb()->toArray());
    }
}
