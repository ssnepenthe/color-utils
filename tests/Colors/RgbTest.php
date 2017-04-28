<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Rgba;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class RgbTest extends TestCase
{
    /** @test */
    function it_is_instantiable()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertInstanceOf(ColorInterface::class, $rgb);
    }

    /** @test */
    function it_forces_a_0_to_255_range_for_colors()
    {
        $this->assertEquals('rgb(0, 0, 0)', new Rgb(-1, -50, -100));
        $this->assertEquals('rgb(255, 255, 255)', new Rgb(256, 300, 350));
    }

    /** @test */
    function it_can_be_cast_to_a_string()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('rgb(255, 0, 51)', (string) $rgb);
        $this->assertEquals('rgb(255, 0, 51)', $rgb->toString());
    }

    /** @test */
    function it_can_calculate_brightness()
    {
        $this->assertEquals(82.059, (new Rgb(255, 0, 51))->calculateBrightness());
    }

    /** @test */
    function it_can_calculate_perceived_brightness()
    {
        $map = [
            '0.0'       => new Rgb(0, 0, 0), // Black.
            '60.07058'  => new Rgb(75, 0, 130), // Indigo.
            '86.09791'  => new Rgb(0, 0, 255), // Blue.
            '98.06838'  => new Rgb(0, 128, 0), // Green.
            '128.0'     => new Rgb(128, 128, 128), // Gray.
            '139.43628' => new Rgb(255, 0, 0), // Red.
            '182.52198' => new Rgb(238, 130, 238), // Violet.
            '188.21145' => new Rgb(255, 165, 0), // Orange.
            '240.02531' => new Rgb(255, 255, 0), // Yellow.
            '255.0'     => new Rgb(255, 255, 255), // White.
        ];

        foreach ($map as $brightness => $color) {
            $brightness = floatval($brightness);

            $this->assertEquals($brightness, $color->calculatePerceivedBrightness());
        }
    }

    /** @test */
    function it_can_calculate_relative_luminance()
    {
        $this->assertEquals(
            0.21499,
            (new Rgb(255, 0, 51))->calculateRelativeLuminance()
        );
    }

    /** @test */
    function channel_getters_give_correct_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals('ff', $rgb->getRedByte());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals('00', $rgb->getGreenByte());
        $this->assertEquals(51, $rgb->getBlue());
        $this->assertEquals('33', $rgb->getBlueByte());
        $this->assertSame(1.0, $rgb->getAlpha());
        $this->assertEquals('ff', $rgb->getAlphaByte());
        $this->assertFalse($rgb->hasAlpha());
        $this->assertEquals('', (new Rgb(255, 0, 51))->getName());
        $this->assertEquals('aquamarine', (new Rgb(127, 255, 212))->getName());
    }

    /** @test */
    function it_can_tell_brightness()
    {
        $this->assertFalse((new Rgb(255, 0, 51))->isBright());
    }

    /** @test */
    function it_can_tell_brightness_with_custom_threshold()
    {
        $this->assertTrue((new Rgb(255, 0, 51))->isBright(80));
    }

    /** @test */
    function it_can_tell_perceived_brightness()
    {
        $this->assertTrue((new Rgb(255, 165, 0))->looksBright()); // Orange.
        $this->assertFalse((new Rgb(0, 0, 255))->looksBright()); // Blue.
    }

    /** @test */
    function it_can_tell_perceived_brightness_with_custom_threshold()
    {
        $this->assertFalse((new Rgb(255, 255, 0))->looksBright(245)); // Yellow.
        $this->assertTrue((new Rgb(0, 0, 255))->looksBright(80)); // Blue.
    }

    /** @test */
    function it_correctly_produces_rgb_array()
    {
        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 51],
            (new Rgb(255, 0, 51))->toArray()
        );
    }

    /** @test */
    function it_correctly_produces_color_instance()
    {
        $this->assertInstanceOf(Color::class, (new Rgb(255, 0, 51))->toColor());
    }

    /** @test */
    function it_correctly_produces_hex_array()
    {
        $this->assertEquals(
            ['red' => 'ff', 'green' => '00', 'blue' => '33'],
            (new Rgb(255, 0, 51))->toHexArray()
        );
    }

    /** @test */
    function it_correctly_produces_hex_string()
    {
        $this->assertEquals('#ff0033', (new Rgb(255, 0, 51))->toHexString());
    }

    /** @test */
    function it_can_create_a_modified_version_of_itself()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('rgb(255, 0, 0)', $rgb->with(['blue' => 0]));
        $this->assertEquals('rgb(0, 0, 0)', $rgb->with(['red' => 0, 'blue' => 0]));
    }

    /** @test */
    function it_can_create_a_version_of_itself_with_transparency()
    {
        $rgba = (new Rgb(255, 0, 51))->with([
            'red' => 0, 'blue' => 0, 'alpha' => 0.7]
        );

        $this->assertInstanceOf(Rgba::class, $rgba);
        $this->assertEquals('rgba(0, 0, 0, 0.7)', $rgba);
    }

    /** @test */
    function it_cant_be_instantiated_with_non_numeric_values()
    {
        $this->expectException(InvalidArgumentException::class);

        new Rgb(123, 234, 'apples');
    }

    /** @test */
    function it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        (new Rgb(1, 2, 3))->with(['hue' => 120]);
    }

    /** @test */
    function it_correctly_pads_hex_bytes()
    {
        $this->assertEquals(
            ['red' => '0a', 'green' => '0b', 'blue' => '0c'],
            (new Rgb(10, 11, 12))->toHexArray()
        );
    }
}
