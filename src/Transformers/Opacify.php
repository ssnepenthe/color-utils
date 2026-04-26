<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Opacify
 */
class Opacify implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['alpha' => $amount]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
