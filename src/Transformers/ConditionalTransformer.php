<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class ConditionalTransformer implements TransformerInterface
{
    protected $callback;
    protected $falsyTransformer = null;
    protected $truthyTransformer;

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
     * @todo If callback returns false should the same color object be returned as it
     *       is now or should a clone of the color object be returned?
     */
    public function transform(ColorInterface $color) : Color
    {
        // Callback always gets a Color instance.
        $color = $color->toColor();

        if (call_user_func($this->callback, $color)) {
            return $this->truthyTransformer->transform($color);
        }

        if (! is_null($this->falsyTransformer)) {
            return $this->falsyTransformer->transform($color);
        }

        return $color;
    }
}
