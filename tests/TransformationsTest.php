<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use function SSNepenthe\ColorUtils\{
    mix, tint, shade, darken, invert, lighten, saturate, grayscale, adjust_hue,
    complement, desaturate, scale_color, adjust_color, change_color
};

class TransformationsTest extends TestCase
{
    /** @test */
    function adjust_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            adjust_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    /** @test */
    function adjust_hue()
    {
        $this->assertEquals(
            'hsl(180, 0%, 0%)',
            adjust_hue(ColorFactory::fromHsl(0, 0, 0), 180)
        );
    }

    /** @test */
    function change_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            change_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    /** @test */
    function complement()
    {
        $this->assertEquals(
            'hsl(180, 0%, 0%)',
            complement(ColorFactory::fromHsl(0, 0, 0))
        );
    }

    /** @test */
    function darken()
    {
        $this->assertEquals(
            'hsl(0, 0%, 70%)',
            darken(ColorFactory::fromHsl(0, 0, 100), 30)
        );
    }

    /** @test */
    function desaturate()
    {
        $this->assertEquals(
            'hsl(0, 95%, 50%)',
            desaturate(ColorFactory::fromHsl(0, 100, 50), 5)
        );
    }

    /** @test */
    function grayscale()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            grayscale(ColorFactory::fromHsl(0, 100, 50))
        );
    }

    /** @test */
    function invert()
    {
        $this->assertEquals(
            'rgb(255, 255, 255)',
            invert(ColorFactory::fromString('black'))
        );
    }

    /** @test */
    function lighten()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            lighten(ColorFactory::fromHsl(0, 0, 0), 50)
        );
    }

    /** @test */
    function mix()
    {
        $this->assertEquals(
            'rgb(128, 0, 128)',
            mix(ColorFactory::fromString('#00f'), ColorFactory::fromString('#f00'))
        );
    }

    /** @test */
    function opacify_and_fade_in()
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

    /** @test */
    function saturate()
    {
        $this->assertEquals(
            'hsl(0, 50%, 0%)',
            saturate(ColorFactory::fromHsl(0, 0, 0), 50)
        );
    }

    /** @test */
    function scale_color()
    {
        $this->assertEquals(
            'hsl(0, 0%, 50%)',
            scale_color(ColorFactory::fromHsl(0, 0, 0), ['lightness' => 50])
        );
    }

    /** @test */
    function shade()
    {
        $this->assertEquals(
            'rgb(128, 128, 128)',
            shade(ColorFactory::fromString('white'))
        );
    }

    /** @test */
    function tint()
    {
        $this->assertEquals(
            'rgb(128, 128, 128)',
            tint(ColorFactory::fromString('black'))
        );
    }

    /** @test */
    function transparentize_and_fade_out()
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
