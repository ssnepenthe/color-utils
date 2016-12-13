<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class Invert implements TransformerInterface
{
    public function transform(ColorInterface $color) : Color
    {
        $color = $color->toColor();

        return $color->with([
            'blue' => 255 - $color->getBlue(),
            'green' => 255 - $color->getGreen(),
            'red' => 255 - $color->getRed(),
        ]);
    }
}
