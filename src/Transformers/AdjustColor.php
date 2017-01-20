<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;

/**
 * Class AdjustColor
 */
class AdjustColor implements TransformerInterface
{
    /**
     * @var array
     */
    protected $channels;

    /**
     * AdjustColor constructor.
     *
     * @param array $channels
     */
    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param Color $color
     * @return Color
     */
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

        foreach ($this->channels as $channel => $adjustment) {
            if (! in_array($channel, $whitelist)) {
                continue;
            }

            $method = 'get' . ucfirst($channel);
            $adjustments[$channel] = $color->{$method}() + $adjustment;
        }

        return $color->with($adjustments);
    }
}
