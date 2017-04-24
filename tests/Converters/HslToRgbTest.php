<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\Rgba;
use SSNepenthe\ColorUtils\Converters\HslToRgb;

class HslToRgbTest extends TestCase
{
    function setUp()
    {
        $this->c = new HslToRgb;
    }

    /** @test */
    function it_can_convert_hsl_and_hsla()
    {
        $hsl = new Hsl(348, 100, 50);
        $hsla = new Hsla(348, 100, 50, 0.7);

        $rgb = $this->c->convert($hsl);
        $rgba = $this->c->convert($hsla);

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertNotInstanceOf(Rgba::class, $rgb);

        $this->assertInstanceOf(Rgba::class, $rgba);

        $this->assertEquals('rgb(255, 0, 51)', $rgb);
        $this->assertEquals('rgba(255, 0, 51, 0.7)', $rgba);
    }

    /** @test */
    function it_correctly_handles_each_step_of_conversion()
    {
        // Step 1 - zero saturation.
        $hsl = new Hsl(0, 0, 83);
        $hsla = new Hsla(0, 0, 83, 0.7);

        $this->assertEquals('rgb(212, 212, 212)', $this->c->convert($hsl));
        $this->assertEquals('rgba(212, 212, 212, 0.7)', $this->c->convert($hsla));

        // Step 2 - lightness under 0.5.
        $hsl = new Hsl(123, 45, 45);
        $hsla = new Hsla(123, 45, 45, 0.7);

        $this->assertEquals('rgb(63, 166, 68)', $this->c->convert($hsl));
        $this->assertEquals('rgba(63, 166, 68, 0.7)', $this->c->convert($hsla));

        // Step 2 - lightness over 0.5.
        $hsl = new Hsl(123, 45, 55);
        $hsla = new Hsla(123, 45, 55, 0.7);

        $this->assertEquals('rgb(89, 192, 94)', $this->c->convert($hsl));
        $this->assertEquals('rgba(89, 192, 94, 0.7)', $this->c->convert($hsla));

        // Step 6 - hue of 0.15.
        $hsl = new Hsl(54, 45, 45);
        $hsla = new Hsla(54, 45, 45, 0.7);

        $this->assertEquals('rgb(166, 156, 63)', $this->c->convert($hsl));
        $this->assertEquals('rgba(166, 156, 63, 0.7)', $this->c->convert($hsla));

        // Step 6 - hue of 0.35.
        $hsl = new Hsl(126, 45, 45);
        $hsla = new Hsla(126, 45, 45, 0.7);

        $this->assertEquals('rgb(63, 166, 73)', $this->c->convert($hsl));
        $this->assertEquals('rgba(63, 166, 73, 0.7)', $this->c->convert($hsla));

        // Step 6 - hue of 0.55.
        $hsl = new Hsl(198, 45, 45);
        $hsla = new Hsla(198, 45, 45, 0.7);

        $this->assertEquals('rgb(63, 135, 166)', $this->c->convert($hsl));
        $this->assertEquals('rgba(63, 135, 166, 0.7)', $this->c->convert($hsla));

        // Step 6 - hue of 0.75.
        $hsl = new Hsl(270, 45, 45);
        $hsla = new Hsla(270, 45, 45, 0.7);

        $this->assertEquals('rgb(115, 63, 166)', $this->c->convert($hsl));
        $this->assertEquals('rgba(115, 63, 166, 0.7)', $this->c->convert($hsla));
    }
}
