<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\tint;
use SSNepenthe\ColorUtils\Transformers\Tint;

class TintTest extends PHPUnit_Framework_TestCase
{
    public function test_tinting_white_just_gives_white()
    {
        $c = Color::fromString('#fff');

        // .tint-white {
        //   color: tint(#fff, 75%); // white
        // }
        $t = new Tint(75);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    public function test_tinting_black_gives_gray()
    {
        $c = Color::fromString('#000');

        // .tint-black {
        //   color: tint(#000, 50%); // gray
        // }
        foreach ([new Tint(50), new Tint] as $t) {
            $this->assertEquals('gray', $t->transform($c)->getName());
        }
    }

    public function test_it_can_tint_red()
    {
        $c = Color::fromString('#f00');

        // .tint-red {
        //   color: tint(#f00, 25%); // #ff4040
        // }
        $t = new Tint(25);
        $this->assertEquals('#ff4040', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_tint_grays()
    {
        $c = Color::fromString('#aaa');

        // .tint-gray {
        //   color: tint(#aaa, 33%); // #c6c6c6
        // }
        $t = new Tint(33);
        $this->assertEquals('#c6c6c6', $t->transform($c)->getRgb()->toHexString());
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
