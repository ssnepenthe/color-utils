<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Darken
 */
class Darken implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['lightness' => -1 * $amount]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
