<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\lighten;
use SSNepenthe\ColorUtils\Transformers\Lighten;

class LightenTest extends TransformerTestCase
{
    public function test_it_can_lighten_hex_colors()
    {
        $color = Color::fromString('#800');

        $tests = [
            // @todo
            // assert_equal("#ee0000", evaluate("lighten(#800, 20%)"))
            ['transformer' => new Lighten(20), 'result' => [240, 0, 0]],
            // assert_equal("white", evaluate("lighten(#800, 100%)"))
            ['transformer' => new Lighten(100), 'result' => [255, 255, 255]],
            // @todo
            // assert_equal("#880000", evaluate("lighten(#800, 0%)"))
            ['transformer' => new Lighten(0), 'result' => [138, 0, 0]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_lighten_hsl_colors()
    {
        $color = Color::fromHsl(0, 0, 0);

        $tests = [
            // assert_equal("#4d4d4d", evaluate("lighten(hsl(0, 0, 0), 30%)"))
            ['transformer' => new Lighten(30), 'result' => [0, 0, 30]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_lighten_rgb_colors()
    {
        $color = Color::fromRgb(136, 0, 0, 0.5);

        $tests = [
            // @todo
            // assert_equal("rgba(238, 0, 0, 0.5)", evaluate("lighten(rgba(136, 0, 0, 0.5), 20%)"))
            ['transformer' => new Lighten(20), 'result' => [240, 0, 0, 0.5]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_only_go_as_light_as_white()
    {
        $color = Color::fromString('white');

        $tests = [
            // assert_equal("white", evaluate("lighten(#fff, 20%)"))
            ['transformer' => new Lighten(20), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new Lighten(50);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 50],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = lighten(Color::fromString('black'), 50);
        $this->assertEquals([0, 0, 50], $color->getHsl()->toArray());
    }
}
