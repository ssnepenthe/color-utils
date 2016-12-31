<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\invert;
use SSNepenthe\ColorUtils\Transformers\Invert;

class InvertTest extends PHPUnit_Framework_TestCase
{
    protected $t;

    public function setUp()
    {
        $this->t = new Invert;
    }

    public function test_it_can_invert_hex_colors()
    {
        $c = Color::fromString('#edc');

        // assert_equal("#112233", evaluate("invert(#edc)"))
        $this->assertEquals(
            '#112233',
            $this->t->transform($c)->getRgb()->toHexString()
        );
    }

    public function test_it_can_invert_rgb_colors()
    {
        $c = Color::fromRgb(10, 20, 30, 0.5);

        // assert_equal("rgba(245, 235, 225, 0.5)", evaluate("invert(rgba(10, 20, 30, 0.5))"))
        $this->assertEquals('rgba(245, 235, 225, 0.5)', $this->t->transform($c));
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        foreach ($colors as $c) {
            $this->assertEquals(
                [255, 255, 255],
                $this->t->transform($c)->getRgb()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $this->assertEquals(
            [255, 255, 255],
            invert(Color::fromString('black'))->getRgb()->toArray()
        );
    }
}
