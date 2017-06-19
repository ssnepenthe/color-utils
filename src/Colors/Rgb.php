<?php

namespace SSNepenthe\ColorUtils\Colors;

use function SSNepenthe\ColorUtils\restrict;
use SSNepenthe\ColorUtils\Parsers\KeywordParser;
use function SSNepenthe\ColorUtils\array_contains_one_of;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class Rgb
 */
class Rgb extends BaseColor
{
    const RED_WEIGHT = 299;
    const GREEN_WEIGHT = 587;
    const BLUE_WEIGHT = 114;

    /**
     * @var int
     */
    protected $blue;

    /**
     * @var int
     */
    protected $green;

    /**
     * @var int
     */
    protected $red;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @throws InvalidArgumentException
     */
    public function __construct($red, $green, $blue)
    {
        $args = [$red, $green, $blue];

        array_walk(
            $args,
            /**
             * @return void
             */
            function ($arg) {
                if (! is_numeric($arg)) {
                    throw new InvalidArgumentException(sprintf(
                        '%s args must be numeric',
                        __METHOD__
                    ));
                }
            }
        );

        $args = array_map(function ($value) : int {
            return restrict(intval(round($value)), 0, 255);
        }, $args);

        list($this->red, $this->green, $this->blue) = $args;
    }

    /**
     * @return float
     */
    public function calculateBrightness() : float
    {
        return round(
            (($this->getRed() * self::RED_WEIGHT)
                + ($this->getGreen() * self::GREEN_WEIGHT)
                + ($this->getBlue() * self::BLUE_WEIGHT))
                / 1000,
            5
        );
    }

    /**
     * @return float
     */
    public function calculatePerceivedBrightness() : float
    {
        return round(sqrt(
            ((self::RED_WEIGHT / 1000) * $this->getRed() * $this->getRed())
            + ((self::GREEN_WEIGHT / 1000) * $this->getGreen() * $this->getGreen())
            + ((self::BLUE_WEIGHT / 1000) * $this->getBlue() * $this->getBlue())
        ), 5);
    }

    /**
     * @return float
     */
    public function calculateRelativeLuminance() : float
    {
        $rgb = array_map(function ($value) : float {
            $value /= 255;

            if ($value <= 0.03928) {
                return $value / 12.92;
            }

            return pow((($value + 0.055) / 1.055), 2.4);
        }, $this->toArray());

        return round(
            (0.2126 * $rgb['red'])
                + (0.7152 * $rgb['green'])
                + (0.0722 * $rgb['blue']),
            5
        );
    }

    /**
     * @return string
     */
    public function getAlphaByte() : string
    {
        return $this->intToHexByte(intval(round($this->alpha * 255)));
    }

    /**
     * @return int
     */
    public function getBlue() : int
    {
        return $this->blue;
    }

    /**
     * @return string
     */
    public function getBlueByte() : string
    {
        return $this->intToHexByte($this->getBlue());
    }

    /**
     * @return int
     */
    public function getGreen() : int
    {
        return $this->green;
    }

    /**
     * @return string
     */
    public function getGreenByte() : string
    {
        return $this->intToHexByte($this->getGreen());
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        if ($name = array_search($this->toHexString(), KeywordParser::MAP)) {
            return $name;
        }

        return '';
    }

    /**
     * @return int
     */
    public function getRed() : int
    {
        return $this->red;
    }

    /**
     * @return string
     */
    public function getRedByte() : string
    {
        return $this->intToHexByte($this->getRed());
    }

    /**
     * @param float $threshold
     * @return bool
     */
    public function isBright($threshold = 127.5) : bool
    {
        $threshold = restrict(floatval($threshold), 0.0, 255.0);

        return $threshold <= $this->calculateBrightness();
    }

    /**
     * @param float $threshold
     * @return bool
     */
    public function looksBright($threshold = 127.5) : bool
    {
        $threshold = restrict(floatval($threshold), 0.0, 255.0);

        return $threshold <= $this->calculatePerceivedBrightness();
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'red'   => $this->getRed(),
            'green' => $this->getGreen(),
            'blue'  => $this->getBlue(),
        ];
    }

    /**
     * @return array
     */
    public function toHexArray() : array
    {
        return [
            'red'   => $this->getRedByte(),
            'green' => $this->getGreenByte(),
            'blue'  => $this->getBlueByte(),
        ];
    }

    /**
     * @return string
     */
    public function toHexString() : string
    {
        return '#' . implode('', $this->toHexArray());
    }

    /**
     * @param array $channels
     * @return ColorInterface
     * @throws InvalidArgumentException
     */
    public function with(array $channels) : ColorInterface
    {
        // You must provide at least one of red, green, blue or alpha.
        if (! array_contains_one_of($channels, ['red', 'green', 'blue', 'alpha'])) {
            throw new InvalidArgumentException(sprintf(
                'One of red, green, blue or alpha is required in %s',
                __METHOD__
            ));
        }

        // Merge defaults.
        extract(array_merge($this->toArray(), $channels));


        if (isset($alpha) && 1.0 != $alpha) {
            return new Rgba($red, $green, $blue, $alpha);
        }

        return new Rgb($red, $green, $blue);
    }

    /**
     * @return string
     */
    protected function getStringPrefix() : string
    {
        return 'rgb';
    }

    /**
     * @param int $int
     * @return string
     */
    protected function intToHexByte(int $int) : string
    {
        return str_pad(dechex($int), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return array
     */
    protected function toStringifiedArray() : array
    {
        return array_map('strval', $this->toArray());
    }
}
