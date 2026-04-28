<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Saturate
 */
class Saturate implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['saturation' => $amount]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
