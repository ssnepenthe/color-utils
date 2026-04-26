<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Tint
 */
class Tint implements TransformerInterface
{
    protected Mix $transformer;

    public function __construct(int $weight = 50)
    {
        $this->transformer = new Mix(new Color(new Rgb(255, 255, 255)), $weight);
    }

    /**
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
