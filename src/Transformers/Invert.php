<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Invert implements TransformerInterface
{
    public function transform(Color $color) : Color
    {
        return $color->with([
            'blue' => 255 - $color->getBlue(),
            'green' => 255 - $color->getGreen(),
            'red' => 255 - $color->getRed(),
        ]);
    }
}
