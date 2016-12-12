<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Saturate implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $amount)
    {
        $this->transformer = new AdjustColor(['saturation' => $amount]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
