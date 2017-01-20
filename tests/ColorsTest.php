<?php

use SSNepenthe\ColorUtils\Colors\Rgba as R;
use SSNepenthe\ColorUtils\Colors\Color as C;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use function SSNepenthe\ColorUtils\{
    alpha, blue, brightness, brightness_difference, color, color_difference,
    contrast_ratio, green, hsl, hsla, hue, is_bright, is_light, lightness,
    looks_bright, name, opacity, perceived_brightness, red, relative_luminance, rgb,
    rgba, saturation
};

class ColorsTest extends PHPUnit_Framework_TestCase
{
    protected $c;

    public function setUp()
    {
        $this->c = new C(new R(255, 0, 51, 0.7));
    }

    public function test_alpha()
    {
        $this->assertEquals(0.7, alpha($this->c));
    }

    public function test_blue()
    {
        $this->assertEquals(51, blue($this->c));
    }

    public function test_brightness()
    {
        $this->assertEquals(82.059, brightness($this->c));
    }

    public function test_brightness_difference()
    {
        $this->assertEquals(143.871, brightness_difference($this->c, color('#ff0')));
    }

    public function test_color()
    {
        $this->assertInstanceOf(C::class, color(255, 0, 51, 0.7));
    }

    public function test_color_difference()
    {
        $this->assertEquals(306, color_difference($this->c, color('#ff0')));
    }

    public function test_contrast_ratio()
    {
        $this->assertEquals(3.68995, contrast_ratio($this->c, color('#ff0')));
    }

    public function test_green()
    {
        $this->assertEquals(0, green($this->c));
    }

    public function test_hsl()
    {
        foreach ([hsl(348, 100, 50), hsl('hsl(348, 100%, 50%)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    public function test_hsla()
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

    public function test_hue()
    {
        $this->assertEquals(348, hue($this->c));
    }

    public function test_is_bright()
    {
        $this->assertFalse(is_bright($this->c));
        $this->assertTrue(is_bright($this->c, 80));
    }

    public function test_is_light()
    {
        $this->assertTrue(is_light($this->c));
        $this->assertFalse(is_light($this->c, 55));
    }

    public function test_lightness()
    {
        $this->assertEquals(50, lightness($this->c));
    }

    public function test_looks_bright()
    {
        $this->assertTrue(looks_bright($this->c));
        $this->assertFalse(looks_bright($this->c, 150));
    }

    public function test_name()
    {
        $this->assertEquals('white', name(ColorFactory::fromString('#ffffff')));
    }

    public function test_opacity()
    {
        $this->assertEquals(0.7, opacity($this->c));
    }

    public function test_perceived_brightness()
    {
        $this->assertEquals(140.49551, perceived_brightness($this->c));
    }

    public function test_red()
    {
        $this->assertEquals(255, red($this->c));
    }

    public function test_relative_luminance()
    {
        $this->assertEquals(0.21499, relative_luminance($this->c));
    }

    public function test_rgb()
    {
        foreach ([rgb(255, 0, 51), rgb('rgb(255, 0, 51)')] as $color) {
            $this->assertInstanceOf(C::class, $color);
        }
    }

    public function test_rgba()
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

    public function test_saturation()
    {
        $this->assertEquals(100, saturation($this->c));
    }
}
