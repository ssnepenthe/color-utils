<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

interface TransformerInterface
{
    public function transform(Color $color) : Color;
}
