<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Darken implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $amount)
    {
        $this->transformer = new AdjustColor(['lightness' => -1 * $amount]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
