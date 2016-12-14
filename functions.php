<?php

namespace SSNepenthe\ColorUtils;

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

function red(ColorInterface $color) : int
{
    return $color->toColor()->getRed();
}

function green(ColorInterface $color) : int
{
    return $color->toColor()->getGreen();
}

function blue(ColorInterface $color) : int
{
    return $color->toColor()->getBlue();
}

function mix(
    ColorInterface $color1,
    ColorInterface $color2,
    int $weight = 50
) : Color {
    $transformer = new Transformers\Mix($color1, $weight);
    return $transformer->transform($color2);
}

function shade(ColorInterface $color, int $weight = 50) : Color
{
    $transformer = new Transformers\Shade($weight);
    return $transformer->transform($color);
}

function tint(ColorInterface $color, int $weight = 50) : Color
{
    $transformer = new Transformers\Tint($weight);
    return $transformer->transform($color);
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

function saturation(ColorInterface $color) : int
{
    return $color->toColor()->getSaturation();
}

function lightness(ColorInterface $color) : int
{
    return $color->toColor()->getLightness();
}

function adjust_hue(ColorInterface $color, int $degrees) : Color
{
    $transformer = new Transformers\AdjustHue($degrees);
    return $transformer->transform($color);
}

function lighten(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Lighten($amount);
    return $transformer->transform($color);
}

function darken(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Darken($amount);
    return $transformer->transform($color);
}

function saturate(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Saturate($amount);
    return $transformer->transform($color);
}

function desaturate(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Desaturate($amount);
    return $transformer->transform($color);
}

function grayscale(ColorInterface $color) : Color
{
    $transformer = new Transformers\GrayScale;
    return $transformer->transform($color);
}

function complement(ColorInterface $color) : Color
{
    $transformer = new Transformers\Complement;
    return $transformer->transform($color);
}

function invert(ColorInterface $color) : Color
{
    $transformer = new Transformers\Invert;
    return $transformer->transform($color);
}

function alpha(ColorInterface $color) : float
{
    return $color->getAlpha();
}

function opacity(ColorInterface $color) : float
{
    return alpha($color);
}

function opacify(ColorInterface $color, float $amount) : Color
{
    $tranformer = new Transformers\Opacify($amount);
    return $tranformer->transform($color);
}

function fade_in(ColorInterface $color, float $amount) : Color
{
    return opacify($color, $amount);
}

function transparentize(ColorInterface $color, float $amount) : Color
{
    $transformer = new Transformers\Transparentize($amount);
    return $transformer->transform($color);
}

function fade_out(ColorInterface $color, float $amount) : Color
{
    return transparentize($color, $amount);
}

function adjust_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\AdjustColor($components);
    return $tranformer->transform($color);
}

function scale_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\ScaleColor($components);
    return $tranformer->transform($color);
}

function change_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\ChangeColor($components);
    return $tranformer->transform($color);
}

function name(ColorInterface $color) : string
{
    return $color->toColor()->getName();
}

function is_light(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->isLight($threshold);
}

function is_dark(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->isDark($threshold);
}

function looks_light(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->looksLight($threshold);
}

function looks_dark(ColorInterface $color, int $threshold = 50) : bool
{
    return $color->toColor()->looksDark($threshold);
}

function perceived_brightness(ColorInterface $color) : int
{
    return $color->toColor()->getPerceivedBrightness();
}
