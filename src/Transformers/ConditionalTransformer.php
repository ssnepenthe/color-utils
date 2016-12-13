<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class ConditionalTransformer implements TransformerInterface
{
    protected $callback;
    protected $else;
    protected $if;

    public function __construct(
        callable $callback,
        TransformerInterface $if,
        TransformerInterface $else = null
    ) {
        $this->callback = $callback;
        $this->if($if);
        $this->else($else);
    }

    public function else(TransformerInterface $else = null)
    {
        $this->else = $else;
    }

    public function if(TransformerInterface $if)
    {
        $this->if = $if;
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
            return $this->if->transform($color);
        }

        if (! is_null($this->else)) {
            return $this->else->transform($color);
        }

        return $color;
    }
}
