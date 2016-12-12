<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Tint implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $weight = 50)
    {
        $this->transformer = new Mix(Color::fromRgb(255, 255, 255), $weight);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
