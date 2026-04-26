<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Desaturate
 */
class Desaturate implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['saturation' => -1 * $amount]);
    }

    /**
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
