<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Opacify implements TransformerInterface
{
    protected $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['alpha' => $amount]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
