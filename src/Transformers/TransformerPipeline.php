<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class TransformerPipeline
 */
class TransformerPipeline implements TransformerInterface
{
    /**
     * @var array
     */
    protected $transformers = [];

    public function __construct(array $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->add($transformer);
        }
    }

    /**
     * @return void
     */
    public function add(TransformerInterface $transformer): void
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        foreach ($this->transformers as $transformer) {
            $color = $transformer->transform($color);
        }

        return $color;
    }
}
