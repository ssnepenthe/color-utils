<?php

namespace SSNepenthe\ColorUtils\Colors;

use function SSNepenthe\ColorUtils\restrict;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class Hsla
 */
class Hsla extends Hsl
{
    /**
     * @param float $hue
     * @param float $saturation
     * @param float $lightness
     * @param float $alpha
     * @throws InvalidArgumentException
     */
    public function __construct($hue, $saturation, $lightness, $alpha)
    {
        if (! is_numeric($alpha)) {
            throw new InvalidArgumentException(sprintf(
                '%s alpha must be numeric',
                __METHOD__
            ));
        }

        parent::__construct($hue, $saturation, $lightness);

        $this->alpha = restrict(floatval($alpha), 0.0, 1.0);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        if (! $this->hasAlpha()) {
            return parent::toArray();
        }

        return array_merge(parent::toArray(), ['alpha' => $this->getAlpha()]);
    }

    /**
     * @return string
     */
    protected function getStringPrefix() : string
    {
        if (! $this->hasAlpha()) {
            return parent::getStringPrefix();
        }

        return 'hsla';
    }

    /**
     * @return array
     */
    protected function toStringifiedArray() : array
    {
        $channels = parent::toStringifiedArray();

        if (! $this->hasAlpha()) {
            return $channels;
        }

        // Single trim with '0.' mask would convert '0.0' to empty string.
        $channels['alpha'] = rtrim(
            rtrim(number_format($this->getAlpha(), 2), '0'),
            '.'
        );

        return $channels;
    }
}
