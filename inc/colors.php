<?php

namespace SSNepenthe\ColorUtils;

function alpha(ColorInterface $color) : float
{
    return $color->getAlpha();
}

function blue(ColorInterface $color) : int
{
    return $color->toColor()->getBlue();
}

function green(ColorInterface $color) : int
{
    return $color->toColor()->getGreen();
}

function hsl(...$args) : Color
{
    $count = count($args);

    if (1 === $count && is_string($args[0])) {
        return Color::fromString(...$args);
    }

    return Color::fromHsl(...$args);
}

function hsla(...$args) : Color
{
    return hsl(...$args);
}

function hue(ColorInterface $color) : int
{
    return $color->toColor()->getHue();
}

function is_dark(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->isDark($threshold);
}

function is_light(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->isLight($threshold);
}

function lightness(ColorInterface $color) : int
{
    return $color->toColor()->getLightness();
}

function looks_dark(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->looksDark($threshold);
}

function looks_light(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->looksLight($threshold);
}

function name(ColorInterface $color) : string
{
    return $color->toColor()->getName();
}

function opacity(ColorInterface $color) : float
{
    return alpha($color);
}

function perceived_brightness(ColorInterface $color) : int
{
    return $color->toColor()->getPerceivedBrightness();
}

function red(ColorInterface $color) : int
{
    return $color->toColor()->getRed();
}

function rgb(...$args) : Color
{
    $count = count($args);

    if (1 === $count && is_string($args[0])) {
        return Color::fromString(...$args);
    }

    return Color::fromRgb(...$args);
}

/**
 * Also set up to adjust opacity of existing color?
 *
 * @link http://sass-lang.com/documentation/Sass/Script/Functions.html#rgba-instance_method
 */
function rgba(...$args) : Color
{
    return rgb(...$args);
}

function saturation(ColorInterface $color) : int
{
    return $color->toColor()->getSaturation();
}
