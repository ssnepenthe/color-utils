<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Desaturate
 */
class Desaturate implements TransformerInterface
{
    /**
     * @var AdjustColor
     */
    protected $transformer;

    /**
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['saturation' => -1 * $amount]);
    }

    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
