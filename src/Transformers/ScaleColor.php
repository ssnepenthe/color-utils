<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;
use function SSNepenthe\ColorUtils\restrict;

/**
 * @todo Should we filter out any non-adjustments? I.e. 0 for any allowed attributes?
 */
class ScaleColor implements TransformerInterface
{
    protected $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = array_map(function (int $adjustment) {
            $adjustment = restrict($adjustment, -100, 100);

            return $adjustment / 100;
        }, $attrs);
    }

    public function transform(ColorInterface $color) : Color
    {
        $color = $color->toColor();

        $whitelist = [
            'alpha'      => [0,   1],
            'blue'       => [0, 255],
            'green'      => [0, 255],
            'hue'        => [0, 360],
            'lightness'  => [0, 100],
            'red'        => [0, 255],
            'saturation' => [0, 100],
        ];

        $adjustments = [];

        foreach ($this->attrs as $attr => $adjustment) {
            if (! in_array($attr, array_keys($whitelist))) {
                continue;
            }

            $getter = 'get' . ucfirst($attr);

            $maxAdjustment = $whitelist[$attr][1] - $color->{$getter}();

            if (0 > $adjustment) {
                $maxAdjustment = $color->{$getter}() - $whitelist[$attr][0];
            }

            $scaleAdjustment = $adjustment * $maxAdjustment;

            $adjustments[$attr] = $color->{$getter}() + $scaleAdjustment;
        }

        return $color->with($adjustments);
    }
}
