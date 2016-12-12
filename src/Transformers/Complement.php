<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Complement implements TransformerInterface
{
    protected $transformer;

    public function __construct()
    {
        $this->transformer = new AdjustColor(['hue' => 180]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
