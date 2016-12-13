<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class TransformerPipeline implements TransformerInterface
{
    protected $transformers = [];

    public function __construct(array $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->add($transformer);
        }
    }

    public function add(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    public function transform(ColorInterface $color) : Color
    {
        foreach ($this->transformers as $transformer) {
            $color = $transformer->transform($color);
        }

        return $color;
    }
}
