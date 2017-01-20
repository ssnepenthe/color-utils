<?php

namespace SSNepenthe\ColorUtils;

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function adjust_color(...$args) : Colors\Color
{
    $components = (array) array_pop($args);
    $color = color(...$args);
    $transformer = new Transformers\AdjustColor($components);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function adjust_hue(...$args) : Colors\Color
{
    $degrees = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\AdjustHue($degrees);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function change_color(...$args) : Colors\Color
{
    $components = (array) array_pop($args);
    $color = color(...$args);
    $transformer = new Transformers\ChangeColor($components);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function complement(...$args) : Colors\Color
{
    $color = color(...$args);
    $transformer = new Transformers\Complement;

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function darken(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Darken($amount);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function desaturate(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Desaturate($amount);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function fade_in(...$args) : Colors\Color
{
    return opacify(...$args);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function fade_out(...$args) : Colors\Color
{
    return transparentize(...$args);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function grayscale(...$args) : Colors\Color
{
    $color = color(...$args);
    $transformer = new Transformers\GrayScale;

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function invert(...$args) : Colors\Color
{
    $color = color(...$args);
    $transformer = new Transformers\Invert;

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function lighten(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Lighten($amount);

    return $transformer->transform($color);
}

/**
 * @param Colors\Color $color1
 * @param Colors\Color $color2
 * @param int $weight
 * @return Colors\Color
 */
function mix(
    Colors\Color $color1,
    Colors\Color $color2,
    int $weight = 50
) : Colors\Color {
    $transformer = new Transformers\Mix($color1, $weight);

    return $transformer->transform($color2);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function opacify(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Opacify($amount);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function saturate(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Saturate($amount);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function scale_color(...$args) : Colors\Color
{
    $components = (array) array_pop($args);
    $color = color(...$args);
    $transformer = new Transformers\ScaleColor($components);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function shade(...$args) : Colors\Color
{
    $weight = 50;

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $weight = intval(array_pop($args));
    }

    $color = color(...$args);
    $transformer = new Transformers\Shade($weight);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function tint(...$args) : Colors\Color
{
    $weight = 50;

    if (_color_args_probably_contain_extra_arg(...$args)) {
        $weight = intval(array_pop($args));
    }

    $color = color(...$args);
    $transformer = new Transformers\Tint($weight);

    return $transformer->transform($color);
}

/**
 * @param mixed ...$args
 * @return Colors\Color
 */
function transparentize(...$args) : Colors\Color
{
    $amount = floatval(array_pop($args));
    $color = color(...$args);
    $transformer = new Transformers\Transparentize($amount);

    return $transformer->transform($color);
}
