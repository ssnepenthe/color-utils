<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Darken
 */
class Darken implements TransformerInterface
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
        $this->transformer = new AdjustColor(['lightness' => -1 * $amount]);
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
