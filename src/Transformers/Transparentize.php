<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Transparentize implements TransformerInterface
{
    protected $transformer;

    public function __construct(float $amount)
    {
        $this->transformer = new AdjustColor(['alpha' => -1 * $amount]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
