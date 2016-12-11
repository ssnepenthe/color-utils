<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Invert Implements TransformerInterface
{
    public function transform(Color $color) : Color
    {
        return $color->with([
            'red' => 255 - $color->getRed(),
            'green' => 255 - $color->getGreen(),
            'blue' => 255 - $color->getBlue()
        ]);
    }
}
