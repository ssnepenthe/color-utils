<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

/**
 * @todo Should we filter out any non-adjustments? I.e. 0 for any allowed attributes?
 */
class ScaleColor implements TransformerInterface
{
    protected $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = array_map(function (int $adjustment) {
            if (-100 > $adjustment) {
                $adjustment = -100;
            }

            if (100 < $adjustment) {
                $adjustment = 100;
            }

            return $adjustment / 100;
        }, $attrs);
    }

    public function transform(Color $color) : Color
    {
        $adjustments = [];

        foreach ($this->attrs as $attr => $adjustment) {
            $whitelist = [
                'red' => [0, 255],
                'green' => [0, 255],
                'blue' => [0, 255],
                'hue' => [0, 360],
                'saturation' => [0, 100],
                'lightness' => [0, 100],
                'alpha' => [0, 1],
            ];

            if (in_array($attr, array_keys($whitelist))) {
                $getter = 'get' . ucfirst($attr);
                $isNegative = 0 > $adjustment;

                if ($isNegative) {
                    $maxAdjustment = $color->{$getter}() - $whitelist[$attr][0];
                } else {
                    $maxAdjustment = $whitelist[$attr][1] - $color->{$getter}();
                }

                $scaleAdjustment = $adjustment * $maxAdjustment;

                $adjustments[$attr] = $color->{$getter}() + $scaleAdjustment;
            }
        }

        return $color->with($adjustments);
    }
}
