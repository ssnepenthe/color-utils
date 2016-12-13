<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class Lighten implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $amount)
    {
        $this->transformer = new AdjustColor(['lightness' => $amount]);
    }

    public function transform(ColorInterface $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
