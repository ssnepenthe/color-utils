<?php

namespace SSNepenthe\ColorUtils\Colors;

use function SSNepenthe\ColorUtils\restrict;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class Rgba
 */
class Rgba extends Rgb
{
    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param float $alpha
     * @throws InvalidArgumentException
     */
    public function __construct($red, $green, $blue, $alpha)
    {
        if (! is_numeric($alpha)) {
            throw new InvalidArgumentException(sprintf(
                '%s alpha must be numeric',
                __METHOD__
            ));
        }

        parent::__construct($red, $green, $blue);

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
     * @return array
     */
    public function toHexArray() : array
    {
        if (! $this->hasAlpha()) {
            return parent::toHexArray();
        }

        return array_merge(parent::toHexArray(), ['alpha' => $this->getAlphaByte()]);
    }

    /**
     * @return string
     */
    protected function getStringPrefix() : string
    {
        if (! $this->hasAlpha()) {
            return parent::getStringPrefix();
        }

        return 'rgba';
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
