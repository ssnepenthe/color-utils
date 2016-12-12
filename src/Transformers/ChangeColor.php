<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class ChangeColor implements TransformerInterface
{
    protected $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = $attrs;
    }

    public function transform(Color $color) : Color
    {
        $adjustments = [];

        foreach ($this->attrs as $attr => $adjustment) {
            $whitelist = [
                'red',
                'green',
                'blue',
                'hue',
                'saturation',
                'lightness',
                'alpha',
            ];

            if (in_array($attr, $whitelist)) {
                $adjustments[$attr] = $adjustment;
            }
        }

        return $color->with($adjustments);
    }
}
