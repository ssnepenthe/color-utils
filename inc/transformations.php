<?php

namespace SSNepenthe\ColorUtils;

function adjust_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\AdjustColor($components);
    return $tranformer->transform($color);
}

function adjust_hue(ColorInterface $color, int $degrees) : Color
{
    $transformer = new Transformers\AdjustHue($degrees);
    return $transformer->transform($color);
}

function change_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\ChangeColor($components);
    return $tranformer->transform($color);
}

function complement(ColorInterface $color) : Color
{
    $transformer = new Transformers\Complement;
    return $transformer->transform($color);
}

function darken(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Darken($amount);
    return $transformer->transform($color);
}

function desaturate(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Desaturate($amount);
    return $transformer->transform($color);
}

function fade_in(ColorInterface $color, float $amount) : Color
{
    return opacify($color, $amount);
}

function fade_out(ColorInterface $color, float $amount) : Color
{
    return transparentize($color, $amount);
}

function grayscale(ColorInterface $color) : Color
{
    $transformer = new Transformers\GrayScale;
    return $transformer->transform($color);
}

function invert(ColorInterface $color) : Color
{
    $transformer = new Transformers\Invert;
    return $transformer->transform($color);
}

function lighten(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Lighten($amount);
    return $transformer->transform($color);
}

function mix(
    ColorInterface $color1,
    ColorInterface $color2,
    int $weight = 50
) : Color {
    $transformer = new Transformers\Mix($color1, $weight);
    return $transformer->transform($color2);
}

function opacify(ColorInterface $color, float $amount) : Color
{
    $tranformer = new Transformers\Opacify($amount);
    return $tranformer->transform($color);
}

function saturate(ColorInterface $color, int $amount) : Color
{
    $transformer = new Transformers\Saturate($amount);
    return $transformer->transform($color);
}

function scale_color(ColorInterface $color, array $components) : Color
{
    $tranformer = new Transformers\ScaleColor($components);
    return $tranformer->transform($color);
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

function transparentize(ColorInterface $color, float $amount) : Color
{
    $transformer = new Transformers\Transparentize($amount);
    return $transformer->transform($color);
}
