<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\Rgba;
use SSNepenthe\ColorUtils\Converters\RgbToHsl;

class RgbToHslTest extends TestCase
{
    function setUp()
    {
        $this->c = new RgbToHsl;
    }

    /** @test */
    function it_can_convert_rgb_and_rgba()
    {
        $rgb = new Rgb(255, 0, 51);
        $rgba = new Rgba(255, 0, 51, 0.7);
        $hsl = $this->c->convert($rgb);
        $hsla = $this->c->convert($rgba);

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertNotInstanceOf(Hsla::class, $hsl);

        $this->assertInstanceOf(Hsla::class, $hsla);

        $this->assertEquals('hsl(348, 100%, 50%)', $hsl);
        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', $hsla);
    }

    /** @test */
    function it_correctly_handles_each_step_of_conversion()
    {
        // Step 4 - Shades of gray.
        $rgb = new Rgb(100, 100, 100);
        $rgba = new Rgba(100, 100, 100, 0.7);


        $this->assertEquals('hsl(0, 0%, 39.21569%)', $this->c->convert($rgb));
        $this->assertEquals('hsla(0, 0%, 39.21569%, 0.7)', $this->c->convert($rgba));

        // Step 5 - lightness under 0.5.
        $rgb = new Rgb(25, 55, 40);
        $rgba = new Rgba(25, 55, 40, 0.7);

        $this->assertEquals('hsl(150, 37.5%, 15.68627%)', $this->c->convert($rgb));
        $this->assertEquals(
            'hsla(150, 37.5%, 15.68627%, 0.7)',
            $this->c->convert($rgba)
        );

        // Step 5 - lightness over 0.5.
        $rgb = new Rgb(100, 125, 150);
        $rgba = new Rgba(100, 125, 150, 0.7);

        $this->assertEquals('hsl(210, 20%, 49.01961%)', $this->c->convert($rgb));
        $this->assertEquals(
            'hsla(210, 20%, 49.01961%, 0.7)',
            $this->c->convert($rgba)
        );

        // Step 6 - max == red.
        $rgb = new Rgb(255, 50, 100);
        $rgba = new Rgba(255, 50, 100, 0.7);

        $this->assertEquals(
            'hsl(345.36585, 100%, 59.80392%)',
            $this->c->convert($rgb)
        );
        $this->assertEquals(
            'hsla(345.36585, 100%, 59.80392%, 0.7)',
            $this->c->convert($rgba)
        );

        // Step 6 - max == green.
        $rgb = new Rgb(50, 255, 100);
        $rgba = new Rgba(50, 255, 100, 0.7);

        $this->assertEquals('hsl(134.63415, 100%, 59.80392%)', $this->c->convert($rgb));
        $this->assertEquals(
            'hsla(134.63415, 100%, 59.80392%, 0.7)',
            $this->c->convert($rgba)
        );

        // Step 6 - max == blue.
        $rgb = new Rgb(50, 100, 255);
        $rgba = new Rgba(50, 100, 255, 0.7);

        $this->assertEquals(
            'hsl(225.36585, 100%, 59.80392%)',
            $this->c->convert($rgb)
        );
        $this->assertEquals(
            'hsla(225.36585, 100%, 59.80392%, 0.7)',
            $this->c->convert($rgba)
        );
    }
}
