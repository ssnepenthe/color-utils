<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\Darken;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Transformers\TransformerInterface;
use SSNepenthe\ColorUtils\Transformers\ConditionalTransformer;

class ConditionalTransformerTest extends TestCase
{
    /** @test */
    function it_can_be_instantiated()
    {
        $transformer = new ConditionalTransformer(function (Color $color) : bool {
            return ! $color->looksBright();
        }, new Lighten(30));

        $this->assertInstanceOf(ConditionalTransformer::class, $transformer);
        $this->assertInstanceOf(TransformerInterface::class, $transformer);
    }

    /** @test */
    function it_can_conditionally_transform_a_color()
    {
        $transformer = new ConditionalTransformer(function (Color $color) : bool {
            return ! $color->looksBright();
        }, new Lighten(30));

        $color = ColorFactory::fromString('orange');
        $newColor = $transformer->transform($color);

        $this->assertSame($color, $newColor);

        $color = ColorFactory::fromString('green');
        $newColor = $transformer->transform($color);

        $this->assertNotSame($color, $newColor);
    }

    /** @test */
    function it_can_apply_a_fallback_transformation()
    {
        $transformer = new ConditionalTransformer(function (Color $color) : bool {
            return ! $color->looksBright();
        }, new Lighten(30), new Darken(30));

        $color = ColorFactory::fromString('orange');
        $darkened = $transformer->transform($color);

        $this->assertEquals('rgb(102, 66, 0)', $darkened);

        $color = ColorFactory::fromString('green');
        $lightened = $transformer->transform($color);

        $this->assertEquals('rgb(26, 255, 26)', $lightened);
    }
}
