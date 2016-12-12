<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class ConditionalTransformer implements TransformerInterface
{
    protected $callback;
    protected $transformer;

    public function __construct(
        callable $callback,
        TransformerInterface $transformer
    ) {
        $this->callback = $callback;
        $this->transformer = $transformer;
    }

    /**
     * @todo If callback returns false should the same color object be returned as it
     *       is now or should a clone of the color object be returned?
     */
    public function transform(ColorInterface $color) : ColorInterface
    {
        if (call_user_func($this->callback, $color)) {
            return $this->transformer->transform($color);
        }

        return $color;
    }
}
