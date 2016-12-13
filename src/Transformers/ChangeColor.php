<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class ChangeColor implements TransformerInterface
{
    protected $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = $attrs;
    }

    public function transform(ColorInterface $color) : Color
    {
        $color = $color->toColor();

        $whitelist = [
            'alpha',
            'blue',
            'green',
            'hue',
            'lightness',
            'red',
            'saturation',
        ];

        $adjustments = [];

        foreach ($this->attrs as $attr => $adjustment) {
            if (! in_array($attr, $whitelist)) {
                continue;
            }

            $adjustments[$attr] = $adjustment;
        }

        return $color->with($adjustments);
    }
}
