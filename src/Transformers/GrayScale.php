<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class GrayScale implements TransformerInterface
{
    protected $transformer;

    public function __construct()
    {
        $this->transformer = new ChangeColor(['saturation' => 0]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
