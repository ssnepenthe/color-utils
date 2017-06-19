<?php

namespace SSNepenthe\ColorUtils\Colors;

use function SSNepenthe\ColorUtils\modulo;
use function SSNepenthe\ColorUtils\restrict;
use function SSNepenthe\ColorUtils\array_contains_one_of;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class Hsl
 */
class Hsl extends BaseColor
{
    /**
     * @var float
     */
    protected $hue;

    /**
     * @var float
     */
    protected $lightness;

    /**
     * @var float
     */
    protected $saturation;

    /**
     * @param float $hue
     * @param float $saturation
     * @param float $lightness
     * @throws InvalidArgumentException
     */
    public function __construct($hue, $saturation, $lightness)
    {
        $args = [$hue, $saturation, $lightness];

        array_walk(
            $args,
            /**
             * @return void
             */
            function ($arg) {
                if (! is_numeric($arg)) {
                    throw new InvalidArgumentException(sprintf(
                        '%s must be called with numeric args',
                        __METHOD__
                    ));
                }
            }
        );

        // Hue.
        $args[0] = modulo(floatval($args[0]), 360);

        // Saturation, lightness.
        for ($i = 1; $i < 3; $i++) {
            $args[$i] = restrict(floatval($args[$i]), 0.0, 100.0);
        }

        list($this->hue, $this->saturation, $this->lightness) = $args;
    }

    /**
     * @return float
     */
    public function getHue() : float
    {
        return round($this->hue, 5);
    }

    /**
     * @return float
     */
    public function getLightness() : float
    {
        return round($this->lightness, 5);
    }

    /**
     * @return float
     */
    public function getSaturation() : float
    {
        return round($this->saturation, 5);
    }

    /**
     * @param float $threshold
     * @return bool
     */
    public function isLight($threshold = 50.0) : bool
    {
        $threshold = restrict(floatval($threshold), 0.0, 100.0);

        return $threshold <= $this->getLightness();
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'hue'        => $this->getHue(),
            'saturation' => $this->getSaturation(),
            'lightness'  => $this->getLightness(),
        ];
    }

    /**
     * @param array $channels
     * @return ColorInterface
     * @throws InvalidArgumentException
     */
    public function with(array $channels) : ColorInterface
    {
        // You must provide at least one of hue, saturation, lightness or alpha.
        if (! array_contains_one_of(
            $channels,
            ['hue', 'saturation', 'lightness', 'alpha']
        )) {
            throw new InvalidArgumentException(
                'One of hue, saturation, lightness or alpha is required'
            );
        }

        // Merge defaults.
        extract(array_merge($this->toArray(), $channels));

        if (isset($alpha) && 1.0 != $alpha) {
            return new Hsla($hue, $saturation, $lightness, $alpha);
        }

        return new Hsl($hue, $saturation, $lightness);
    }

    /**
     * @return string
     */
    protected function getStringPrefix() : string
    {
        return 'hsl';
    }

    /**
     * @return array
     */
    protected function toStringifiedArray() : array
    {
        $channels = array_map('strval', $this->toArray());
        $channels['saturation'] = $channels['saturation'] . '%';
        $channels['lightness'] = $channels['lightness'] . '%';

        return $channels;
    }
}
