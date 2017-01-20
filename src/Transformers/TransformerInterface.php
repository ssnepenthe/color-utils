<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color;
}
