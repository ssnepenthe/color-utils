<?php

namespace SSNepenthe\ColorUtils\Converters;

use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Rgba;
use function SSNepenthe\ColorUtils\modulo;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use function SSNepenthe\ColorUtils\array_contains_all_of;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class HslToRgb
 */
class HslToRgb implements ConverterInterface
{
    /**
     * @param ColorInterface $color
     * @return ColorInterface
     * @throws InvalidArgumentException
     */
    public function convert(ColorInterface $color) : ColorInterface
    {
        $channels = $color->toArray();

        if (! array_contains_all_of($channels, ['hue', 'saturation', 'lightness'])) {
            throw new InvalidArgumentException(sprintf(
                'Hue, saturation and lightness are all required in %s',
                __METHOD__
            ));
        }

        extract($channels);

        // 0) We want saturation and lightness on a scale of 0 - 1.
        $saturation = $saturation / 100;
        $lightness = $lightness / 100;

        // 1) No saturation means no hue means color is a shade of gray.
        if (0 === $saturation) {
            $red = $green = $blue = $lightness * 255;

            if (isset($alpha)) {
                return new Rgba($red, $green, $blue, $alpha);
            }

            return new Rgb($red, $green, $blue);
        }

        // 2/3) Temporary vars.
        $temp1 = $lightness + $saturation - $lightness * $saturation;

        if ($lightness <= 0.5) {
            $temp1 = $lightness * (1 + $saturation);
        }

        $temp2 = 2 * $lightness - $temp1;

        // 4) Get hue on a scale of 0 - 1.
        $hue = $hue / 360;

        // 5) Temporary colors.
        $tempColors = array_map(function ($colorValue) : float {
            return modulo($colorValue, 1);
        }, ['red' => $hue + (1 / 3), 'green' => $hue, 'blue' => $hue - (1 / 3)]);

        // 6) Actual color values.
        $colors = array_map(function ($colorValue) use ($temp1, $temp2) : float {
            if (6 * $colorValue < 1) {
                return $temp2 + ($temp1 - $temp2) * 6 * $colorValue;
            }

            if (2 * $colorValue < 1) {
                return $temp1;
            }

            if (3 * $colorValue < 2) {
                return $temp2 + ($temp1 - $temp2) * ((2 / 3) - $colorValue) * 6;
            }

            return $temp2;
        }, $tempColors);

        // 7) Convert to 8-bit and extract.
        extract(array_map(function ($color) : float {
            return $color * 255;
        }, $colors));

        if (isset($alpha)) {
            return new Rgba($red, $green, $blue, $alpha);
        }

        return new Rgb($red, $green, $blue);
    }
}
