<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\GrayScale;

class GrayScaleTest extends TransformerTestCase
{
    public function test_it_can_convert_hex_colors_to_grayscale()
    {
        $color = Color::fromString('#abc');

        $tests = [
            // @todo Off by one from SASS.
            // assert_equal("#bbbbbb", evaluate("grayscale(#abc)"))
            ['transformer' => new GrayScale, 'result' => [186, 186, 186]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_convert_hsl_colors_to_grayscale()
    {
        $color = Color::fromHsl(25, 100, 80);

        $tests = [
            ['transformer' => new GrayScale, 'result' => [25, 0, 80]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_convert_rgb_colors_to_grayscale()
    {
        $color = Color::fromRgb(136, 0, 0);

        $tests = [
            ['transformer' => new GrayScale, 'result' => [69, 69, 69]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_doesnt_change_shades_of_gray()
    {
        $color = Color::fromString('#fff');

        $tests = [
            // assert_equal("white", evaluate("grayscale(white)"))
            ['transformer' => new GrayScale, 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromString('#000');

        $tests = [
            // assert_equal("black", evaluate("grayscale(black)"))
            ['transformer' => new GrayScale, 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_converts_primary_colors_straight_to_gray()
    {
        $color = Color::fromString('#f00');

        $tests = [
            // assert_equal("gray", evaluate("grayscale(#f00)"))
            ['transformer' => new GrayScale, 'result' => [128, 128, 128]]
        ];

        $this->runTransformerTests($color, $tests);

        $color = Color::fromString('#00f');

        $tests = [
            // assert_equal("gray", evaluate("grayscale(#00f)"))
            ['transformer' => new GrayScale, 'result' => [128, 128, 128]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('red'),
            Rgb::fromString('red'),
            Hsl::fromString('hsl(0, 100%, 50%)'),
        ];

        $transformer = new GrayScale;

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 50],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }
}
