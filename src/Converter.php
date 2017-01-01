<?php

namespace SSNepenthe\ColorUtils;

/**
 * @link http://www.niwa.nu/2013/05/math-behind-colorspace-conversions-rgb-hsl/
 *       https://www.w3.org/TR/css3-color/#hsl-color
 *       https://github.com/sass/sass/blob/stable/lib/sass/script/value/color.rb#L639
 *       https://github.com/sass/sass/blob/stable/lib/sass/script/value/color.rb#L665
 */
class Converter
{
    public function hslToRgb(Hsl $hsl) : Rgb
    {
        // 0) We want saturation and lightness on a scale of 0 - 1.
        $saturation = $hsl->getSaturation() / 100;
        $lightness = $hsl->getLightness() / 100;

        // 1) No saturation means no hue means color is a shade of gray.
        if (0 === $saturation) {
            $colors = array_fill(0, 3, $lightness * 255);

            if ($hsl->hasAlpha()) {
                $colors[] = $hsl->getAlpha();
            }

            return new Rgb(...$colors);
        }

        // 2/3) Temporary vars.
        if ($lightness <= 0.5) {
            $temp1 = $lightness * (1 + $saturation);
        } else {
            $temp1 = $lightness + $saturation - $lightness * $saturation;
        }

        $temp2 = 2 * $lightness - $temp1;

        // 4) Get hue on a scale of 0 - 1.
        $hue = $hsl->getHue() / 360;

        // 5) Temporary colors.
        $tempColors = array_map(function ($colorValue) {
            return modulo($colorValue, 1);
        }, [$hue + (1 / 3), $hue, $hue - (1 / 3)]);

        // 6) Actual color values.
        $colors = array_map(function ($colorValue) use ($temp1, $temp2) {
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

        // 7) Convert to 8-bit.
        $colors = array_map(function ($color) {
            return $color * 255;
        }, $colors);

        if ($hsl->hasAlpha()) {
            $colors[] = $hsl->getAlpha();
        }

        return new Rgb(...$colors);
    }

    public function rgbToHsl(Rgb $rgb) : Hsl
    {
        // 1) Get RGB values in a range of 0-1.
        list($red, $green, $blue) = array_map(function ($value) {
            return $value / 255;
        }, $rgb->toArray());

        // 2) Find the max and min values from $red, $green, $blue.
        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);

        // 3) Calculate lightness.
        $lightness = ($max + $min) / 2;

        // 4) Equal max/min means equal colors means this is a shade of gray.
        if ($max === $min) {
            $colors = [0, 0, $lightness * 100];

            if ($rgb->hasAlpha()) {
                $colors[] = $rgb->getAlpha();
            }

            return new Hsl(...$colors);
        }

        // 5) Calculate saturation.
        if ($lightness < 0.5) {
            $saturation = ($max - $min) / ($max + $min);
        } else {
            $saturation = ($max - $min) / (2 - $max - $min);
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
        }

        // Return to proper scale.
        $colors = [$hue * 60, $saturation * 100, $lightness * 100];

        if ($rgb->hasAlpha()) {
            $colors[] = $rgb->getAlpha();
        }

        return new Hsl(...$colors);
    }
}
