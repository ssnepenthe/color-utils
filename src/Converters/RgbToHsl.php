<?php

namespace SSNepenthe\ColorUtils\Converters;

use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use SSNepenthe\ColorUtils\Exceptions\LogicException;
use function SSNepenthe\ColorUtils\array_contains_all_of;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class RgbToHsl
 */
class RgbToHsl implements ConverterInterface
{
    /**
     * @param ColorInterface $color
     * @return ColorInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function convert(ColorInterface $color) : ColorInterface
    {
        $channels = $color->toArray();

        if (! array_contains_all_of($channels, ['red', 'green', 'blue'])) {
            throw new InvalidArgumentException(sprintf(
                'Red, green and blue are all required in %s',
                __METHOD__
            ));
        }

        extract($channels);

        // 1) Get RGB values in a range of 0-1.
        list($red, $green, $blue) = array_map(function ($value) : float {
            return $value / 255;
        }, [$red, $green, $blue]);

        // 2) Find the max and min values from $red, $green, $blue.
        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);

        // 3) Calculate lightness.
        $lightness = ($max + $min) / 2;

        // 4) Equal max/min means equal colors means this is a shade of gray.
        if ($max === $min) {
            $hue = $saturation = 0;
            $lightness *= 100;

            if (isset($alpha)) {
                return new Hsla($hue, $saturation, $lightness, $alpha);
            }

            return new Hsl($hue, $saturation, $lightness);
        }

        // 5) Calculate saturation.
        $saturation = ($max - $min) / (2 - $max - $min);

        if ($lightness < 0.5) {
            $saturation = ($max - $min) / ($max + $min);
        }

        // 6) Calculate hue.
        switch ($max) {
            case $red:
                $hue = ($green - $blue) / ($max - $min);
                break;
            case $green:
                $hue = 2 + ($blue - $red) / ($max - $min);
                break;
            case $blue:
                $hue = 4 + ($red - $green) / ($max - $min);
                break;
            default:
                throw new LogicException(
                    'It should be impossible for this exception to be thrown... What have you done?'
                );
        }

        // Return to proper scale.
        $hue *= 60;
        $saturation *= 100;
        $lightness *= 100;

        if (isset($alpha)) {
            return new Hsla($hue, $saturation, $lightness, $alpha);
        }

        return new Hsl($hue, $saturation, $lightness);
    }
}
