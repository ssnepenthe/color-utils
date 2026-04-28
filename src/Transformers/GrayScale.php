<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class GrayScale
 */
class GrayScale implements TransformerInterface
{
    protected ChangeColor $transformer;

    public function __construct()
    {
        $this->transformer = new ChangeColor(['saturation' => 0]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
