<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Desaturate;

class DesaturateTest extends TransformerTestCase
{
    public function test_it_can_desaturate_hsl_colors()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal("#e3e8e3", evaluate("desaturate(hsl(120, 30, 90), 20%)"))
            ['transformer' => new Desaturate(20), 'result' => [120, 10, 90]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_desaturate_hex_colors()
    {
        $color = Color::fromHex('#855');

        $tests = [
            // @todo Off by one from SASS.
            // assert_equal("#726b6b", evaluate("desaturate(#855, 20%)"))
            ['transformer' => new Desaturate(20), 'result' => [113, 106, 106]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_desaturate_rgb_colors()
    {
        $color = Color::fromRgb(136, 85, 85);

        $tests = [
            // @todo Off by one from SASS.
            // assert_equal("rgba(114, 107, 107, 0.5)", evaluate("desaturate(rgba(136, 85, 85, 0.5), 20%)"))
            ['transformer' => new Desaturate(20), 'result' => [113, 106, 106]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function it_cant_desaturate_shades_of_gray()
    {
        $color = Color::fromHex('#000');

        $tests = [
            // assert_equal("black", evaluate("desaturate(#000, 20%)"))
            ['transformer' => new Desaturate(20), 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromHex('#fff');

        $tests = [
            // assert_equal("white", evaluate("desaturate(#fff, 20%)"))
            ['transformer' => new Desaturate(20), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_handles_the_extremes()
    {
        $color = Color::fromHex('#8a8');

        $tests = [
            // assert_equal("#999999", evaluate("desaturate(#8a8, 100%)"))
            ['transformer' => new Desaturate(100), 'result' => [153, 153, 153]],
            // assert_equal("#88aa88", evaluate("desaturate(#8a8, 0%)"))
            ['transformer' => new Desaturate(0), 'result' => [136, 170, 136]],
        ];

        $this->runTransformerTests($color, $tests);
    }
}
