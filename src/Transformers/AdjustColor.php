<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

/**
 * @todo Should we filter out any non-adjustments? I.e. hue in multiples of 360 and
 *       0 for any of the other allowed attributes?
 */
class AdjustColor implements TransformerInterface
{
    protected $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = $attrs;
    }

    public function transform(Color $color) : Color
    {
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

            $method = 'get' . ucfirst($attr);
            $adjustments[$attr] = $color->{$method}() + $adjustment;
        }

        return $color->with($adjustments);
    }
}
