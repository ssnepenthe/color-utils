<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

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

    public function transform(Color $color) : Color
    {
        foreach ($this->transformers as $transformer) {
            $color = $transformer->transform($color);
        }

        return $color;
    }
}
