<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

class Invert implements TransformerInterface
{
    public function transform(ColorInterface $color) : ColorInterface
    {
        return $color->with([
            'red' => 255 - $color->getRed(),
            'green' => 255 - $color->getGreen(),
            'blue' => 255 - $color->getBlue()
        ]);
    }
}
