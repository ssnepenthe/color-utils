<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class AdjustHue implements TransformerInterface
{
    protected $transformer;

    public function __construct(int $amount)
    {
        $this->transformer = new AdjustColor(['hue' => $amount]);
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        return $this->transformer->transform($color);
    }
}
