<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Shade implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $weight = 50)
    {
        $this->transformer = new Mix(Color::fromRgb(0, 0, 0), $weight);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
