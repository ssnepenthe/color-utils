<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Shade;
use SSNepenthe\ColorUtils\Transformers\Invert;
use SSNepenthe\ColorUtils\Transformers\Lighten;
use SSNepenthe\ColorUtils\Transformers\AdjustColor;
use SSNepenthe\ColorUtils\Transformers\TransformerPipeline;
use SSNepenthe\ColorUtils\Transformers\TransformerInterface;

class TransformerPipelineTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated()
    {
        $pipeline = new TransformerPipeline;

        $this->assertInstanceOf(TransformerPipeline::class, $pipeline);
        $this->assertInstanceOf(TransformerInterface::class, $pipeline);
    }

    public function test_it_can_add_transformers()
    {
        $pipeline = new TransformerPipeline;
        $lighten30 = new Lighten(30);

        $pipeline->add($lighten30);

        $this->assertAttributeContains($lighten30, 'transformers', $pipeline);
    }

    public function test_it_can_be_isntantiated_with_transformers()
    {
        $lighten30 = new Lighten(30);
        $pipeline = new TransformerPipeline([$lighten30]);

        $this->assertAttributeContains($lighten30, 'transformers', $pipeline);
    }

    public function test_it_can_transform_a_color()
    {
        $pipeline = new TransformerPipeline([new Lighten(30)]);
        $color = $pipeline->transform(Color::fromString('green'));

        // Untransformed green would be [0, 128, 0].
        $this->assertEquals([26, 255, 26], $color->toArray());
    }

    public function test_it_transforms_colors_in_the_order_transformers_were_added()
    {
        $invert = new Invert;
        $shade25 = new Shade(25);
        $color = Color::fromString('green');

        $pipeline = new TransformerPipeline;
        $pipeline->add($invert); // from [0, 128, 0] to [255, 127, 255].
        $pipeline->add($shade25); // from [255, 127, 255] to [191, 95, 191].

        $this->assertEquals([191, 95, 191], $pipeline->transform($color)->toArray());

        $pipeline = new TransformerPipeline;
        $pipeline->add($shade25); // from [0, 128, 0] to [0, 96, 0].
        $pipeline->add($invert); // from [0, 96, 0] to [255, 159, 255].

        $this->assertEquals(
            [255, 159, 255],
            $pipeline->transform($color)->toArray()
        );
    }
}
