<?php

namespace SSNepenthe\ColorUtils;

function adjust_color(Color $color, array $components) : Color
{
    $tranformer = new Transformers\AdjustColor($components);
    return $tranformer->transform($color);
}

function adjust_hue(Color $color, int $degrees) : Color
{
    $transformer = new Transformers\AdjustHue($degrees);
    return $transformer->transform($color);
}

function change_color(Color $color, array $components) : Color
{
    $tranformer = new Transformers\ChangeColor($components);
    return $tranformer->transform($color);
}

function complement(Color $color) : Color
{
    $transformer = new Transformers\Complement;
    return $transformer->transform($color);
}

function darken(Color $color, int $amount) : Color
{
    $transformer = new Transformers\Darken($amount);
    return $transformer->transform($color);
}

function desaturate(Color $color, int $amount) : Color
{
    $transformer = new Transformers\Desaturate($amount);
    return $transformer->transform($color);
}

function fade_in(Color $color, float $amount) : Color
{
    return opacify($color, $amount);
}

function fade_out(Color $color, float $amount) : Color
{
    return transparentize($color, $amount);
}

function grayscale(Color $color) : Color
{
    $transformer = new Transformers\GrayScale;
    return $transformer->transform($color);
}

function invert(Color $color) : Color
{
    $transformer = new Transformers\Invert;
    return $transformer->transform($color);
}

function lighten(Color $color, int $amount) : Color
{
    $transformer = new Transformers\Lighten($amount);
    return $transformer->transform($color);
}

function mix(Color $color1, Color $color2, int $weight = 50) : Color {
    $transformer = new Transformers\Mix($color1, $weight);
    return $transformer->transform($color2);
}

function opacify(Color $color, float $amount) : Color
{
    $tranformer = new Transformers\Opacify($amount);
    return $tranformer->transform($color);
}

function saturate(Color $color, int $amount) : Color
{
    $transformer = new Transformers\Saturate($amount);
    return $transformer->transform($color);
}

function scale_color(Color $color, array $components) : Color
{
    $tranformer = new Transformers\ScaleColor($components);
    return $tranformer->transform($color);
}

function shade(Color $color, int $weight = 50) : Color
{
    $transformer = new Transformers\Shade($weight);
    return $transformer->transform($color);
}

function tint(Color $color, int $weight = 50) : Color
{
    $transformer = new Transformers\Tint($weight);
    return $transformer->transform($color);
}

function transparentize(Color $color, float $amount) : Color
{
    $transformer = new Transformers\Transparentize($amount);
    return $transformer->transform($color);
}
