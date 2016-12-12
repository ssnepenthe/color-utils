<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class GrayScale Implements TransformerInterface
{
    protected $transformer;

    public function __construct()
    {
        $this->transformer = new ChangeColor(['saturation' => 0]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
