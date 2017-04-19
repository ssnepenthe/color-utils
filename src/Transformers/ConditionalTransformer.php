<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class ConditionalTransformer
 */
class ConditionalTransformer implements TransformerInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var TransformerInterface|null
     */
    protected $falsyTransformer = null;

    /**
     * @var TransformerInterface
     */
    protected $truthyTransformer;

    /**
     * @param callable $callback
     * @param TransformerInterface $truthyTransformer
     * @param TransformerInterface|null $falsyTransformer
     */
    public function __construct(
        callable $callback,
        TransformerInterface $truthyTransformer,
        TransformerInterface $falsyTransformer = null
    ) {
        $this->callback = $callback;
        $this->truthyTransformer = $truthyTransformer;
        $this->falsyTransformer = $falsyTransformer;
    }

    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        if (call_user_func($this->callback, $color)) {
            return $this->truthyTransformer->transform($color);
        }

        if (! is_null($this->falsyTransformer)) {
            return $this->falsyTransformer->transform($color);
        }

        return $color;
    }
}
