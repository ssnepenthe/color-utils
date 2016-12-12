<?php

use SSNepenthe\ColorUtils\Color;
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

        $color = Color::fromKeyword('red');
        $newColor = $transformer->transform($color);

        $this->assertSame($color, $newColor);

        $color = Color::fromKeyword('green');
        $newColor = $transformer->transform($color);

        $this->assertNotSame($color, $newColor);
    }
}