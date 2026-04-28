<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Transparentize
 */
class Transparentize implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['alpha' => -1 * $amount]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
