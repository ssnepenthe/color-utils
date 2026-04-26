<?php

namespace SSNepenthe\ColorUtils;

/**
 * @param mixed ...$args
 */
function alpha(...$args) : float
{
    return color(...$args)->getAlpha();
}

/**
 * @param mixed ...$args
 */
function blue(...$args) : int
{
    return color(...$args)->getRgb()->getBlue();
}

/**
 * @param mixed ...$args
 */
function brightness(...$args) : float
{
    return color(...$args)->getRgb()->calculateBrightness();
}

function brightness_difference(Colors\Color $color1, Colors\Color $color2) : float
{
    return $color1->calculateBrightnessDifferenceWith($color2);
}

/**
 * @param mixed ...$args
 */
function color(...$args) : Colors\Color
{
    return Colors\ColorFactory::fromUnknown(...$args);
}

function color_difference(Colors\Color $color1, Colors\Color $color2) : int
{
    return $color1->calculateColorDifferenceWith($color2);
}

function contrast_ratio(Colors\Color $color1, Colors\Color $color2) : float
{
    return $color1->calculateContrastRatioWith($color2);
}

/**
 * @param mixed ...$args
 */
function green(...$args) : int
{
    return color(...$args)->getRgb()->getGreen();
}

/**
 * @param mixed ...$args
 */
function hsl(...$args) : Colors\Color
{
    if (3 === count($args)) {
        return Colors\ColorFactory::fromHsl(...$args);
    }

    return Colors\ColorFactory::fromUnknownOneArg(...$args);
}

/**
 * @param mixed ...$args
 */
function hsla(...$args) : Colors\Color
{
    if (4 === count($args)) {
        return Colors\ColorFactory::fromHsla(...$args);
    }

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $alpha = array_pop($args);

        return Colors\ColorFactory::fromUnknownOneArg(...$args)
            ->with(['alpha' => $alpha]);
    }

    return Colors\ColorFactory::fromUnknownOneArg(...$args);
}

/**
 * @param mixed ...$args
 */
function hue(...$args) : float
{
    return color(...$args)->getHsl()->getHue();
}

/**
 * @param mixed ...$args
 */
function is_bright(...$args) : bool
{
    $threshold = 127.5;

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $threshold = array_pop($args);
    }

    return color(...$args)->getRgb()->isBright($threshold);
}

/**
 * @param mixed ...$args
 */
function is_light(...$args) : bool
{
    $threshold = 50.0;

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $threshold = array_pop($args);
    }

    return color(...$args)->getHsl()->isLight($threshold);
}

/**
 * @param mixed ...$args
 */
function lightness(...$args) : float
{
    return color(...$args)->getHsl()->getLightness();
}

/**
 * @param mixed ...$args
 */
function looks_bright(...$args) : bool
{
    $threshold = 127.5;

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $threshold = array_pop($args);
    }

    return color(...$args)->getRgb()->looksBright($threshold);
}

/**
 * @param mixed ...$args
 */
function name(...$args) : string
{
    return color(...$args)->getRgb()->getName();
}

/**
 * @param mixed ...$args
 */
function opacity(...$args) : float
{
    return alpha(...$args);
}

/**
 * @param mixed ...$args
 */
function perceived_brightness(...$args) : float
{
    return color(...$args)->getRgb()->calculatePerceivedBrightness();
}

/**
 * @param mixed ...$args
 */
function red(...$args) : int
{
    return color(...$args)->getRgb()->getRed();
}

/**
 * @param mixed ...$args
 */
function relative_luminance(...$args) : float
{
    return color(...$args)->getRgb()->calculateRelativeLuminance();
}

/**
 * @param mixed ...$args
 */
function rgb(...$args) : Colors\Color
{
    if (3 === count($args)) {
        return Colors\ColorFactory::fromRgb(...$args);
    }

    return Colors\ColorFactory::fromUnknownOneArg(...$args);
}

/**
 * @param mixed ...$args
 */
function rgba(...$args) : Colors\Color
{
    if (4 === count($args)) {
        return Colors\ColorFactory::fromRgba(...$args);
    }

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $alpha = array_pop($args);

        return Colors\ColorFactory::fromUnknownOneArg(...$args)
            ->with(['alpha' => $alpha]);
    }

    return Colors\ColorFactory::fromUnknownOneArg(...$args);
}

/**
 * @param mixed ...$args
 */
function saturation(...$args) : float
{
    return color(...$args)->getHsl()->getSaturation();
}
