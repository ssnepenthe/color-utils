<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Complement Implements TransformerInterface
{
    protected $transformer;

    public function __construct()
    {
        $this->transformer = new AdjustColor(['hue' => 180]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
