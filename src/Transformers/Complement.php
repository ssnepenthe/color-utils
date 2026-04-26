<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class Complement
 */
class Complement implements TransformerInterface
{
    protected AdjustColor $transformer;

    public function __construct()
    {
        $this->transformer = new AdjustColor(['hue' => 180]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
