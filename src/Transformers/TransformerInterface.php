<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    /**
     * @return Color
     */
    public function transform(Color $color) : Color;
}
