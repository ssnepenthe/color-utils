<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;
use function SSNepenthe\ColorUtils\restrict;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class ScaleColor
 */
class ScaleColor implements TransformerInterface
{
    /**
     * @var array
     */
    protected $adjustments = [];

    /**
     * Maps allowed channels to the maximum value of each.
     *
     * @var array
     */
    protected $whitelist = [
        'alpha'      => 1,
        'blue'       => 255,
        'green'      => 255,
        'hue'        => 360,
        'lightness'  => 100,
        'red'        => 255,
        'saturation' => 100,
    ];

    /**
     * @param array $adjustments
     * @throws InvalidArgumentException
     */
    public function __construct(array $adjustments)
    {
        // First filter out non-numeric adjustments.
        $adjustments = array_filter($adjustments, function ($adjustment) : bool {
            return is_numeric($adjustment);
        });

        foreach ($this->whitelist as $channel => $_) {
            if (isset($adjustments[$channel])) {
                $this->adjustments[$channel] = restrict(
                    $adjustments[$channel],
                    -100,
                    100
                ) / 100;
            }
        }

        if (empty($this->adjustments)) {
            throw new InvalidArgumentException(sprintf(
                'No valid adjustments provided in %s',
                __METHOD__
            ));
        }
    }

    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        $channels = [];

        foreach ($this->adjustments as $channel => $adjustment) {
            $getter = 'get' . ucfirst($channel);
            $maxAdjustment = $this->whitelist[$channel] - $color->{$getter}();

            if (0 > $adjustment) {
                $maxAdjustment = $color->{$getter}();
            }

            $scaleAdjustment = $adjustment * $maxAdjustment;

            $channels[$channel] = $color->{$getter}() + $scaleAdjustment;
        }

        return $color->with($channels);
    }
}
