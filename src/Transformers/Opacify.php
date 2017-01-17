<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Opacify implements TransformerInterface
{
    protected $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['alpha' => $amount]);
    }

    public function transform(Color $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
