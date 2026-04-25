<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use SSNepenthe\ColorUtils\Colors\Rgba as R;
use SSNepenthe\ColorUtils\Colors\Color as C;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use function SSNepenthe\ColorUtils\{
    alpha, blue, brightness, brightness_difference, color, color_difference,
    contrast_ratio, green, hsl, hsla, hue, is_bright, is_light, lightness,
    looks_bright, name, opacity, perceived_brightness, red, relative_luminance, rgb,
    rgba, saturation
};

class ColorsTest extends TestCase
{
    protected $c;

    function set_up()
    {
        $this->c = new C(new R(255, 0, 51, 0.7));
    }

    /** @test */
    function alpha(): void
    {
        $this->assertEquals(0.7, alpha($this->c));
    }

    /** @test */
    function blue(): void
    {
        $this->assertEquals(51, blue($this->c));
    }

    /** @test */
    function brightness(): void
    {
        $this->assertEquals(82.059, brightness($this->c));
    }

    /** @test */
    function brightness_difference(): void
    {
        $this->assertEquals(143.871, brightness_difference($this->c, color('#ff0')));
    }

    /** @test */
    function color(): void
    {
        $this->assertInstanceOf(C::class, color(255, 0, 51, 0.7));
    }

    /** @test */
    function color_difference(): void
    {
        $this->assertEquals(306, color_difference($this->c, color('#ff0')));
    }

    /** @test */
    function contrast_ratio(): void
    {
        $this->assertEquals(3.68995, contrast_ratio($this->c, color('#ff0')));
    }

    /** @test */
    function green(): void
    {
        $this->assertEquals(0, green($this->c));
    }

    /** @test */
    function hsl(): void
    {
        foreach ([hsl(348, 100, 50), hsl('hsl(348, 100%, 50%)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    /** @test */
    function hsla(): void
    {
        $colors = [
            hsla(348, 100, 50, 0.7),
            hsla('hsl(348, 100%, 50%)', 0.7),
            hsla('hsla(348, 100%, 50%, 0.7)')
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(C::class, $color);
            $this->assertEquals('hsla(348, 100%, 50%, 0.7)', $color);
        }
    }

    /** @test */
    function hue(): void
    {
        $this->assertEquals(348, hue($this->c));
    }

    /** @test */
    function is_bright(): void
    {
        $this->assertFalse(is_bright($this->c));
        $this->assertTrue(is_bright($this->c, 80));
    }

    /** @test */
    function is_light(): void
    {
        $this->assertTrue(is_light($this->c));
        $this->assertFalse(is_light($this->c, 55));
    }

    /** @test */
    function lightness(): void
    {
        $this->assertEquals(50, lightness($this->c));
    }

    /** @test */
    function looks_bright(): void
    {
        $this->assertTrue(looks_bright($this->c));
        $this->assertFalse(looks_bright($this->c, 150));
    }

    /** @test */
    function name(): void
    {
        $this->assertEquals('white', name(ColorFactory::fromString('#ffffff')));
    }

    /** @test */
    function opacity(): void
    {
        $this->assertEquals(0.7, opacity($this->c));
    }

    /** @test */
    function perceived_brightness(): void
    {
        $this->assertEquals(140.49551, perceived_brightness($this->c));
    }

    /** @test */
    function red(): void
    {
        $this->assertEquals(255, red($this->c));
    }

    /** @test */
    function relative_luminance(): void
    {
        $this->assertEquals(0.21499, relative_luminance($this->c));
    }

    /** @test */
    function rgb(): void
    {
        foreach ([rgb(255, 0, 51), rgb('rgb(255, 0, 51)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    /** @test */
    function rgba(): void
    {
        $colors = [
            rgba(255, 0, 51, 0.7),
            rgba('rgb(255, 0, 51)', 0.7),
            rgba('rgba(255, 0, 51, 0.7)')
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(C::class, $color);
            $this->assertEquals('rgba(255, 0, 51, 0.7)', $color);
        }
    }

    /** @test */
    function saturation(): void
    {
        $this->assertEquals(100, saturation($this->c));
    }
}
