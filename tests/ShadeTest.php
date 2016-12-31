<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\shade;
use SSNepenthe\ColorUtils\Transformers\Shade;

class ShadeTest extends PHPUnit_Framework_TestCase
{
    public function test_shading_white_gives_gray()
    {
        $c = Color::fromString('#fff');

        // .shade-white {
        //   color: shade(#fff, 75%); // #404040
        // }
        $t = new Shade(75);
        $this->assertEquals('#404040', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_shading_black_just_gives_black()
    {
        $c = Color::fromString('#000');

        // .shade-black {
        //   color: shade(#000, 50%); // black
        // }
        // Also tests that 50 is default amount.
        foreach ([new Shade(50), new Shade] as $t) {
            $this->assertEquals('black', $t->transform($c)->getName());
        }
    }

    public function test_it_can_shade_red()
    {
        $c = Color::fromString('#f00');

        // .shade-red {
        //   color: shade(#f00, 25%); // #bf0000
        // }
        $t = new Shade(25);
        $this->assertEquals('#bf0000', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_shade_grays()
    {
        $c = Color::fromString('#222');

        // .shade-gray {
        //   color: shade(#222, 33%); // #171717
        // }
        $t = new Shade(33);
        $this->assertEquals('#171717', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('white'),
            Rgb::fromString('white'),
            Hsl::fromString('hsl(0, 0%, 100%)'),
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
