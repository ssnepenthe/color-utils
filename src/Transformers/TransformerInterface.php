<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

interface TransformerInterface
{
    public function transform(ColorInterface $color) : ColorInterface;
}
