<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class Complement implements TransformerInterface
{
    protected $transformer;

    public function __construct()
    {
        $this->transformer = new AdjustColor(['hue' => 180]);
    }

    public function transform(ColorInterface $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
