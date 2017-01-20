<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;
use function SSNepenthe\ColorUtils\restrict;

/**
 * Class ScaleColor
 */
class ScaleColor implements TransformerInterface
{
    /**
     * @var array
     */
    protected $channels;

    /**
     * ScaleColor constructor.
     *
     * @param array $channels
     */
    public function __construct(array $channels)
    {
        $this->channels = array_map(function (int $adjustment) {
            $adjustment = restrict($adjustment, -100, 100);

            return $adjustment / 100;
        }, $channels);
    }

    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        // Map allowed channels to max value of each.
        $whitelist = [
            'alpha'      => 1,
            'blue'       => 255,
            'green'      => 255,
            'hue'        => 360,
            'lightness'  => 100,
            'red'        => 255,
            'saturation' => 100,
        ];

        $adjustments = [];

        foreach ($this->channels as $channel => $adjustment) {
            if (! in_array($channel, array_keys($whitelist))) {
                continue;
            }

            $getter = 'get' . ucfirst($channel);

            $maxAdjustment = $whitelist[$channel] - $color->{$getter}();

            if (0 > $adjustment) {
                $maxAdjustment = $color->{$getter}();
            }

            $scaleAdjustment = $adjustment * $maxAdjustment;

            $adjustments[$channel] = $color->{$getter}() + $scaleAdjustment;
        }

        return $color->with($adjustments);
    }
}
