<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\invert;
use SSNepenthe\ColorUtils\Transformers\Invert;

class InvertTest extends TransformerTestCase
{
    public function test_it_can_invert_hex_colors()
    {
        $color = Color::fromString('#edc');

        $tests = [
            // assert_equal("#112233", evaluate("invert(#edc)"))
            ['transformer' => new Invert, 'result' => [17, 34, 51]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_invert_rgb_colors()
    {
        $color = Color::fromRgb(10, 20, 30, 0.5);

        $tests = [
            // assert_equal("rgba(245, 235, 225, 0.5)", evaluate("invert(rgba(10, 20, 30, 0.5))"))
            ['transformer' => new Invert, 'result' => [245, 235, 225, 0.5]]
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

        $transformer = new Invert;

        foreach ($colors as $color) {
            $this->assertEquals(
                [255, 255, 255],
                $transformer->transform($color)->getRgb()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = invert(Color::fromString('black'));
        $this->assertEquals([255, 255, 255], $color->getRgb()->toArray());
    }
}
