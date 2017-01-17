<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class Shade implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $weight = 50)
    {
        $this->transformer = new Mix(new Color(new Rgb(0, 0, 0)), $weight);
    }

    public function transform(ColorInterface $color) : Color
    {
        return $this->transformer->transform($color);
    }
}
