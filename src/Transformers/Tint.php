<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Tint
 */
class Tint implements TransformerInterface
{
    /**
     * @var Mix
     */
    protected $transformer;

    /**
     * @param int $weight
     */
    public function __construct(int $weight = 50)
    {
        $this->transformer = new Mix(new Color(new Rgb(255, 255, 255)), $weight);
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
