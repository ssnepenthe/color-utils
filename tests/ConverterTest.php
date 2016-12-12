<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Converter;

/**
 * Conversions verified online using the SASSMeister and RGB.to.
 *
 * @link http://www.sassmeister.com/
 * @link http://rgb.to
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->converter = new Converter;
    }

    public function test_it_can_convert_hsl_to_rgb()
    {
        $hsl = new Hsl(348, 100, 50);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertInstanceOf(Rgb::class, $rgb);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(51, $rgb->getBlue());
    }

    /**
     * Test step 1.
     */
    public function test_it_can_convert_hsl_with_saturation_of_zero_to_rgb()
    {
        $hsl = new Hsl(0, 0, 83);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(212, $rgb->getRed());
        $this->assertEquals(212, $rgb->getGreen());
        $this->assertEquals(212, $rgb->getBlue());
    }

    /**
     * Test step 2.
     */
    public function test_it_can_convert_hsl_with_lightness_under_one_half_to_rgb()
    {
        $hsl = new Hsl(123, 45, 45);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(63, $rgb->getRed());
        $this->assertEquals(166, $rgb->getGreen());
        $this->assertEquals(68, $rgb->getBlue());
    }

    /**
     * Test step 2.
     */
    public function test_it_can_convert_hsl_with_lightness_over_one_half_to_rgb()
    {
        $hsl = new Hsl(123, 45, 55);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(89, $rgb->getRed());
        $this->assertEquals(192, $rgb->getGreen());
        $this->assertEquals(94, $rgb->getBlue());
    }

    /**
     * Test step 6 against green channel.
     */
    public function test_it_can_convert_hsl_with_hue_of_point_one_five_to_rgb()
    {
        $hsl = new Hsl(54, 45, 45);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(166, $rgb->getRed());
        $this->assertEquals(156, $rgb->getGreen());
        $this->assertEquals(63, $rgb->getBlue());
    }

    /**
     * Test step 6 against green channel.
     */
    public function test_it_can_convert_hsl_with_hue_of_point_three_five_to_rgb()
    {
        $hsl = new Hsl(126, 45, 45);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(63, $rgb->getRed());
        $this->assertEquals(166, $rgb->getGreen());
        $this->assertEquals(73, $rgb->getBlue());
    }

    /**
     * Test step 6 against green channel.
     */
    public function test_it_can_convert_hsl_with_hue_of_point_five_five_to_rgb()
    {
        $hsl = new Hsl(198, 45, 45);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(63, $rgb->getRed());
        $this->assertEquals(135, $rgb->getGreen());
        $this->assertEquals(166, $rgb->getBlue());
    }

    /**
     * Test step 6 against green channel.
     */
    public function test_it_can_convert_hsl_with_hue_of_point_seven_five_to_rgb()
    {
        $hsl = new Hsl(270, 45, 45);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertEquals(115, $rgb->getRed());
        $this->assertEquals(63, $rgb->getGreen());
        $this->assertEquals(166, $rgb->getBlue());
    }

    public function test_it_converts_hsl_with_alpha_when_appropriate()
    {
        $hsl = new Hsl(348, 100, 50);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertFalse($rgb->hasAlpha());

        $hsla = new Hsl(348, 100, 50, 0.7);
        $rgba = $this->converter->hslToRgb($hsla);

        $this->assertTrue($rgba->hasAlpha());

        $hsl = new Hsl(0, 0, 83);
        $rgb = $this->converter->hslToRgb($hsl);

        $this->assertFalse($rgb->hasAlpha());

        $hsla = new Hsl(0, 0, 83, 0.7);
        $rgba = $this->converter->hslToRgb($hsla);

        $this->assertTrue($rgba->hasAlpha());
    }

    public function test_it_can_convert_rgb_to_hsl()
    {
        $rgb = new Rgb(255, 0, 51);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(348, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(50, $hsl->getLightness());
    }

    public function test_it_can_convert_rgb_shades_of_gray()
    {
        $rgb = new Rgb(100, 100, 100);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(0, $hsl->getHue());
        $this->assertEquals(0, $hsl->getSaturation());
        $this->assertEquals(39, $hsl->getLightness());
    }

    /**
     * Test step 5.
     */
    public function test_it_can_convert_rgb_with_lightness_under_one_half()
    {
        $rgb = new Rgb(25, 55, 40);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(150, $hsl->getHue());
        $this->assertEquals(38, $hsl->getSaturation());
        $this->assertEquals(16, $hsl->getLightness());
    }

    /**
     * Test step 5.
     */
    public function test_it_can_convert_rgb_with_lightness_over_one_half()
    {
        $rgb = new Rgb(100, 125, 150);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(210, $hsl->getHue());
        $this->assertEquals(20, $hsl->getSaturation());
        $this->assertEquals(49, $hsl->getLightness());
    }

    /**
     * Test step 6.
     */
    public function test_it_can_convert_rgb_where_red_has_highest_value()
    {
        $rgb = new Rgb(255, 50, 100);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(345, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(60, $hsl->getLightness());
    }

    /**
     * Test step 6.
     */
    public function test_it_can_convert_rgb_where_green_has_highest_value()
    {
        $rgb = new Rgb(50, 255, 100);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(135, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(60, $hsl->getLightness());
    }

    /**
     * Test step 6.
     */
    public function test_it_can_convert_rgb_where_blue_has_highest_value()
    {
        $rgb = new Rgb(50, 100, 255);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertEquals(225, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(60, $hsl->getLightness());
    }

    public function test_it_converts_rgb_with_alpha_when_appropriate()
    {
        $rgb = new Rgb(255, 0, 51);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertFalse($hsl->hasAlpha());

        $rgba = new Rgb(255, 0, 51, 0.7);
        $hsla = $this->converter->rgbToHsl($rgba);

        $this->assertTrue($hsla->hasAlpha());

        $rgb = new Rgb(55, 55, 55);
        $hsl = $this->converter->rgbToHsl($rgb);

        $this->assertFalse($hsl->hasAlpha());

        $rgba = new Rgb(55, 55, 55, 0.7);
        $hsla = $this->converter->rgbToHsl($rgba);

        $this->assertTrue($hsla->hasAlpha());
    }
}
