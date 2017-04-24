<?php

use PHPUnit\Framework\TestCase;
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

    public function setUp()
    {
        $this->c = new C(new R(255, 0, 51, 0.7));
    }

    /** @test */
    public function alpha()
    {
        $this->assertEquals(0.7, alpha($this->c));
    }

    /** @test */
    public function blue()
    {
        $this->assertEquals(51, blue($this->c));
    }

    /** @test */
    public function brightness()
    {
        $this->assertEquals(82.059, brightness($this->c));
    }

    /** @test */
    public function brightness_difference()
    {
        $this->assertEquals(143.871, brightness_difference($this->c, color('#ff0')));
    }

    /** @test */
    public function color()
    {
        $this->assertInstanceOf(C::class, color(255, 0, 51, 0.7));
    }

    /** @test */
    public function color_difference()
    {
        $this->assertEquals(306, color_difference($this->c, color('#ff0')));
    }

    /** @test */
    public function contrast_ratio()
    {
        $this->assertEquals(3.68995, contrast_ratio($this->c, color('#ff0')));
    }

    /** @test */
    public function green()
    {
        $this->assertEquals(0, green($this->c));
    }

    /** @test */
    public function hsl()
    {
        foreach ([hsl(348, 100, 50), hsl('hsl(348, 100%, 50%)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    /** @test */
    public function hsla()
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
    public function hue()
    {
        $this->assertEquals(348, hue($this->c));
    }

    /** @test */
    public function is_bright()
    {
        $this->assertFalse(is_bright($this->c));
        $this->assertTrue(is_bright($this->c, 80));
    }

    /** @test */
    public function is_light()
    {
        $this->assertTrue(is_light($this->c));
        $this->assertFalse(is_light($this->c, 55));
    }

    /** @test */
    public function lightness()
    {
        $this->assertEquals(50, lightness($this->c));
    }

    /** @test */
    public function looks_bright()
    {
        $this->assertTrue(looks_bright($this->c));
        $this->assertFalse(looks_bright($this->c, 150));
    }

    /** @test */
    public function name()
    {
        $this->assertEquals('white', name(ColorFactory::fromString('#ffffff')));
    }

    /** @test */
    public function opacity()
    {
        $this->assertEquals(0.7, opacity($this->c));
    }

    /** @test */
    public function perceived_brightness()
    {
        $this->assertEquals(140.49551, perceived_brightness($this->c));
    }

    /** @test */
    public function red()
    {
        $this->assertEquals(255, red($this->c));
    }

    /** @test */
    public function relative_luminance()
    {
        $this->assertEquals(0.21499, relative_luminance($this->c));
    }

    /** @test */
    public function rgb()
    {
        foreach ([rgb(255, 0, 51), rgb('rgb(255, 0, 51)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    /** @test */
    public function rgba()
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
    public function saturation()
    {
        $this->assertEquals(100, saturation($this->c));
    }
}
