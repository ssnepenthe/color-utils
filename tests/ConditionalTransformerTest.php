<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Darken;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Transformers\TransformerInterface;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

class ConditionalTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated()
    {
        $transformer = new ConditionalTransformer(function(Color $color) : bool {
            return $color->looksDark();
        }, new Lighten(30));

        $this->assertInstanceOf(ConditionalTransformer::class, $transformer);
        $this->assertInstanceOf(TransformerInterface::class, $transformer);
    }

    public function test_it_can_conditionally_transform_a_color()
    {
        $transformer = new ConditionalTransformer(function(Color $color) : bool {
            return $color->looksDark();
        }, new Lighten(30));

        $color = Color::fromString('red');
        $newColor = $transformer->transform($color);

        $this->assertSame($color, $newColor);

        $color = Color::fromString('green');
        $newColor = $transformer->transform($color);

        $this->assertNotSame($color, $newColor);
    }

    public function test_it_can_apply_a_fallback_transformation()
    {
        $transformer = new ConditionalTransformer(function(Color $color) : bool {
            return $color->looksDark();
        }, new Lighten(30), new Darken(30));

        $color = Color::fromString('red');
        $newColor = $transformer->transform($color);

        $this->assertEquals([102, 0, 0], $newColor->toArray()); // Darkened.

        $color = Color::fromString('green');
        $newColor = $transformer->transform($color);

        $this->assertEquals([26, 255, 26], $newColor->toArray()); // Lightened.
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new ConditionalTransformer(function ($color) {
            return $color->isDark();
        }, new Lighten(30));

        foreach ($colors as $color) {
            $this->assertEquals(
                [0, 0, 30],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }
}
