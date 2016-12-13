<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\shade;
use SSNepenthe\ColorUtils\Transformers\Shade;

class ShadeTest extends TransformerTestCase
{
    public function test_shading_white_gives_gray()
    {
        $color = Color::fromString('#fff');

        $tests = [
            // .shade-white {
            //   color: shade(#fff, 75%); // #404040
            // }
            ['transformer' => new Shade(75), 'result' => [64, 64, 64]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_shading_black_just_gives_black()
    {
        $color = Color::fromString('#000');

        $tests = [
            // .shade-black {
            //   color: shade(#000, 50%); // black
            // }
            ['transformer' => new Shade(50), 'result' => [0, 0, 0]],
            // Test that 50 is default Shade amount.
            ['transformer' => new Shade, 'result' => [0, 0, 0]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_shade_red()
    {
        $color = Color::fromString('#f00');

        $tests = [
            // .shade-red {
            //   color: shade(#f00, 25%); // #bf0000
            // }
            ['transformer' => new Shade(25), 'result' => [191, 0, 0]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_shade_grays()
    {
        $color = Color::fromString('#222');

        $tests = [
            // .shade-gray {
            //   color: shade(#222, 33%); // #171717
            // }
            ['transformer' => new Shade(33), 'result' => [23, 23, 23]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('white'),
            Rgb::fromString('white'),
            Hsl::fromString('hsl(0, 0%, 1000%)'),
        ];

        $transformer = new Shade;

        foreach ($colors as $color) {
            $this->assertEquals(
                [128, 128, 128],
                $transformer->transform($color)->getRgb()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = shade(Color::fromString('white'));
        $this->assertEquals([128, 128, 128], $color->getRgb()->toArray());
    }
}
