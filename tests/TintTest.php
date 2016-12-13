<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\tint;
use SSNepenthe\ColorUtils\Transformers\Tint;

class TintTest extends TransformerTestCase
{
    public function test_tinting_white_just_gives_white()
    {
        $color = Color::fromString('#fff');

        $tests = [
            // .tint-white {
            //   color: tint(#fff, 75%); // white
            // }
            ['transformer' => new Tint(75), 'result' => [255, 255, 255]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_tinting_black_gives_gray()
    {
        $color = Color::fromString('#000');

        $tests = [
            // .tint-black {
            //   color: tint(#000, 50%); // gray
            // }
            ['transformer' => new Tint(50), 'result' => [128, 128, 128]],
            // Test that 50 is default Tint amount.
            ['transformer' => new Tint, 'result' => [128, 128, 128]]
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_tint_red()
    {
        $color = Color::fromString('#f00');

        $tests = [
            // .tint-red {
            //   color: tint(#f00, 25%); // #ff4040
            // }
            ['transformer' => new Tint(25), 'result' => [255, 64, 64]],
        ];

        $this->runTransformerTests($color, $tests);
    }

    public function test_it_can_tint_grays()
    {
        $color = Color::fromString('#aaa');

        $tests = [
            // .tint-gray {
            //   color: tint(#aaa, 33%); // #c6c6c6
            // }
            ['transformer' => new Tint(33), 'result' => [198, 198, 198]],
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

        $transformer = new Tint;

        foreach ($colors as $color) {
            $this->assertEquals(
                [128, 128, 128],
                $transformer->transform($color)->getRgb()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = tint(Color::fromString('black'));
        $this->assertEquals([128, 128, 128], $color->getRgb()->toArray());
    }
}
