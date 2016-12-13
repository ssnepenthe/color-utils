<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Transparentize;

class TransparentizeTest extends TransformerTestCase
{
    public function test_it_can_add_transparency_to_colors()
    {
        $transformer = new Transparentize(0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("transparentize(rgba(0, 0, 0, 0.5), 0.2)"))
        $this->assertEquals(
            [0, 0, 0, 0.3],
            $transformer->transform(Color::fromRgb(0, 0, 0, 0.5))->toArray()
        );

        // assert_equal("transparent", evaluate("fade_out(rgba(0, 0, 0, 0.2), 0.2)"))
        $this->assertEquals(
            [0, 0, 0, 0.0],
            $transformer->transform(Color::fromRgb(0, 0, 0, 0.2))->toArray()
        );

        $transformer = new Transparentize(0);

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0)"))
        $this->assertEquals(
            [0, 0, 0, 0.2],
            $transformer->transform(Color::fromRgb(0, 0, 0, 0.2))->toArray()
        );

        $transformer = new Transparentize(0.1);

        // assert_equal("rgba(0, 0, 0, 0.1)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0.1)"))
        $this->assertEquals(
            [0, 0, 0, 0.1],
            $transformer->transform(Color::fromRgb(0, 0, 0, 0.2))->toArray()
        );

        $transformer = new Transparentize(1);

        // assert_equal("transparent", evaluate("transparentize(rgba(0, 0, 0, 0.2), 1)"))
        $this->assertEquals(
            [0, 0, 0, 0.0],
            $transformer->transform(Color::fromRgb(0, 0, 0, 1))->toArray()
        );
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new Transparentize(0.5);

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 0, 0.5],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }
}
