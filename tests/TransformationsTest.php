<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use function SSNepenthe\ColorUtils\{
    mix, tint, shade, darken, invert, lighten, saturate, grayscale, adjust_hue,
    complement, desaturate, scale_color, adjust_color, change_color
};

class TransformationsTest extends TestCase
{
    public function test_adjust_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            adjust_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    public function test_adjust_hue()
    {
        $this->assertEquals(
            'hsl(180, 0%, 0%)',
            adjust_hue(ColorFactory::fromHsl(0, 0, 0), 180)
        );
    }

    public function test_change_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            change_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    public function test_complement()
    {
        $this->assertEquals(
            'hsl(180, 0%, 0%)',
            complement(ColorFactory::fromHsl(0, 0, 0))
        );
    }

    public function test_darken()
    {
        $this->assertEquals(
            'hsl(0, 0%, 70%)',
            darken(ColorFactory::fromHsl(0, 0, 100), 30)
        );
    }

    public function test_desaturate()
    {
        $this->assertEquals(
            'hsl(0, 95%, 50%)',
            desaturate(ColorFactory::fromHsl(0, 100, 50), 5)
        );
    }

    public function test_grayscale()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            grayscale(ColorFactory::fromHsl(0, 100, 50))
        );
    }

    public function test_invert()
    {
        $this->assertEquals(
            'rgb(255, 255, 255)',
            invert(ColorFactory::fromString('black'))
        );
    }

    public function test_lighten()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            lighten(ColorFactory::fromHsl(0, 0, 0), 50)
        );
    }

    public function test_mix()
    {
        $this->assertEquals(
            'rgb(128, 0, 128)',
            mix(ColorFactory::fromString('#00f'), ColorFactory::fromString('#f00'))
        );
    }

    public function test_opacify_and_fade_in()
    {
        $c = ColorFactory::fromRgba(0, 0, 0, 0);
        $functions = [
            'SSNepenthe\\ColorUtils\\opacify',
            'SSNepenthe\\ColorUtils\\fade_in',
        ];

        foreach ($functions as $function) {
            $this->assertEquals('rgba(0, 0, 0, 0.5)', $function($c, 0.5));
        }
    }

    public function test_saturate()
    {
        $this->assertEquals(
            'hsl(0, 50%, 0%)',
            saturate(ColorFactory::fromHsl(0, 0, 0), 50)
        );
    }

    public function test_scale_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            scale_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    public function test_shade()
    {
        $this->assertEquals(
            'rgb(128, 128, 128)',
            shade(ColorFactory::fromString('white'))
        );
    }

    public function test_tint()
    {
        $this->assertEquals(
            'rgb(128, 128, 128)',
            tint(ColorFactory::fromString('black'))
        );
    }

    public function test_transparentize_and_fade_out()
    {
        $c = ColorFactory::fromRgb(0, 0, 0);
        $functions = [
            'SSNepenthe\\ColorUtils\\transparentize',
            'SSNepenthe\\ColorUtils\\fade_out',
        ];

        foreach ($functions as $function) {
            $this->assertEquals('rgba(0, 0, 0, 0.5)', $function($c, 0.5));
        }
    }
}
