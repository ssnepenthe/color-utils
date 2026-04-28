<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    public function transform(Color $color) : Color;
}
