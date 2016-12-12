<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\AdjustHue;

class AdjustHueTest extends TransformerTestCase
{
    public function test_it_can_positively_adjust_hue_from_hsl()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal("#deeded", evaluate("adjust-hue(hsl(120, 30, 90), 60deg)"))
            ['transformer' => new AdjustHue(60), 'result' => [180, 30, 90]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_positively_adjust_hue_from_hex()
    {
        $color = Color::fromHex('#811');

        $tests = [
            // assert_equal("#886a11", evaluate("adjust-hue(#811, 45deg)"))
            ['transformer' => new AdjustHue(45), 'result' => [136, 106, 17]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_positively_adjust_hue_from_rgb()
    {
        $color = Color::fromRgb(136, 17, 17, 0.5);

        $tests = [
            // assert_equal("rgba(136, 106, 17, 0.5)", evaluate("adjust-hue(rgba(136, 17, 17, 0.5), 45deg)"))
            ['transformer' => new AdjustHue(45), 'result' => [136, 106, 17, 0.5]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_negatively_adjust_hue()
    {
        $color = Color::fromHsl(120, 30, 90);

        $tests = [
            // assert_equal("#ededde", evaluate("adjust-hue(hsl(120, 30, 90), -60deg)"))
            ['transformer' => new AdjustHue(-60), 'result' => [60, 30, 90]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_cant_adjust_shades_of_gray()
    {
        $color = Color::fromHex('#000');

        $tests = [
            // assert_equal("black", evaluate("adjust-hue(#000, 45deg)"))
            ['transformer' => new AdjustHue(45), 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromHex('#fff');

        $tests = [
            // assert_equal("white", evaluate("adjust-hue(#fff, 45deg)"))
            ['transformer' => new AdjustHue(45), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_adjustments_of_0_or_360_dont_change_a_color()
    {
        $color = Color::fromHex('#8a8');

        $tests = [
            // assert_equal("#88aa88", evaluate("adjust-hue(#8a8, 360deg)"))
            ['transformer' => new AdjustHue(360), 'result' => [136, 170, 136]],
            // assert_equal("#88aa88", evaluate("adjust-hue(#8a8, 0deg)"))
            ['transformer' => new AdjustHue(0), 'result' => [136, 170, 136]],
        ];

        $this->runTransformerTests($color, $tests);
    }
}
