<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\name;
use SSNepenthe\ColorUtils\ColorInterface;

class RgbTest extends PHPUnit_Framework_TestCase
{
    public function test_it_implements_color_interface()
    {
        $rgb = new Rgb(255, 0, 51);
        $this->assertInstanceOf(ColorInterface::class, $rgb);
    }

    public function test_all_object_properties_are_stored_as_correct_type()
    {
        $colors = [
            new Rgb(255, 0, 51),
            new Rgb(255, 0, 51, 1),
        ];

        foreach ($colors as $color) {
            $this->assertAttributeInternalType('int', 'red', $color);
            $this->assertAttributeInternalType('int', 'green', $color);
            $this->assertAttributeInternalType('int', 'blue', $color);
            $this->assertAttributeInternalType('float', 'alpha', $color);
        }
    }

    public function test_it_is_instantiable()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals([255, 0, 51], $rgb->toArray());

        $rgba = new Rgb(255, 0, 51, 0.7);

        $this->assertInstanceOf(Rgb::class, $rgba);
        $this->assertEquals([255, 0, 51, 0.7], $rgba->toArray());
    }

    public function test_it_can_be_cast_to_a_string()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('rgb(255, 0, 51)', (string) $rgb);

        $rgba = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals('rgba(255, 0, 51, 0.7)', (string) $rgba);
    }

    public function test_it_gives_the_correct_alpha_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertFalse($rgb->hasAlpha());
        $this->assertEquals(1.0, $rgb->getAlpha());
        $this->assertEquals('ff', $rgb->getAlphaByte());

        $rgb = new Rgb(255, 0, 51, 0.7);

        $this->assertTrue($rgb->hasAlpha());
        $this->assertEquals(0.7, $rgb->getAlpha());
        $this->assertEquals('b3', $rgb->getAlphaByte());
    }

    public function test_it_gives_the_correct_blue_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(51, $rgb->getBlue());
        $this->assertEquals('33', $rgb->getBlueByte());
    }

    public function test_it_gives_the_correct_green_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals('00', $rgb->getGreenByte());
    }

    public function test_it_gives_the_correct_name_value()
    {
        $rgb = Rgb::fromString('#ff0033');

        $this->assertEquals('', $rgb->getName());

        $rgb = Rgb::fromString('#7fffd4');

        $this->assertEquals('aquamarine', $rgb->getName());
        $this->assertEquals('aquamarine', name($rgb));

        $rgb = Rgb::fromString('#b22222');

        $this->assertEquals('firebrick', $rgb->getName());
    }

    public function test_it_gives_the_correct_red_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals('ff', $rgb->getRedByte());
    }

    public function test_it_correctly_produces_rgb_array()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals([255, 0, 51], $rgb->toArray());
    }

    public function test_it_correctly_produces_hex_array()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(['ff', '00', '33'], $rgb->toHexArray());
    }

    public function test_it_correctly_produces_hex_string()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('#ff0033', $rgb->toHexString());

        $rgba = new Rgb(255, 0, 51, 1.0);

        $this->assertEquals('#ff0033ff', $rgba->toHexString());
    }

    public function test_it_correctly_pads_hex_bytes()
    {
        $rgb = new Rgb(10, 11, 12);

        $this->assertEquals('0a', $rgb->getRedByte());
        $this->assertEquals('0b', $rgb->getGreenByte());
        $this->assertEquals('0c', $rgb->getBlueByte());
    }

    public function test_it_correctly_produces_rgb_string()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('rgb(255, 0, 51)', $rgb->toString());

        $rgba = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals('rgba(255, 0, 51, 0.7)', $rgba->toString());
    }

    public function test_it_correctly_produces_color_instance()
    {
        $this->assertInstanceOf(Color::class, Rgb::fromString('#f03')->toColor());
    }

    public function test_it_forces_a_0_to_255_range_for_colors()
    {
        $rgb = new Rgb(-1, -50, -100);

        $this->assertEquals([0, 0, 0,], $rgb->toArray());

        $rgb = new Rgb(256, 300, 350);

        $this->assertEquals([255, 255, 255], $rgb->toArray());
    }

    public function test_it_forces_a_0_to_1_range_for_alpha()
    {
        $rgb = new Rgb(255, 0, 51, -0.3);

        $this->assertEquals(0.0, $rgb->getAlpha());

        $rgb = new Rgb(255, 0, 51, 1.3);

        $this->assertEquals(1.0, $rgb->getAlpha());
    }

    public function test_it_is_instantiable_using_color_keywords()
    {
        $rgb = Rgb::fromString('black');

        $this->assertEquals([0, 0, 0], $rgb->toArray());

        $rgb = Rgb::fromString('transparent');

        $this->assertEquals([0, 0, 0, 0], $rgb->toArray());
    }

    public function test_it_is_instantiable_using_hex_notation()
    {
        $colors = [
            Rgb::fromString('#f03'),
            Rgb::fromString('#F03'),
            Rgb::fromString('#ff0033'),
            Rgb::fromString('#FF0033'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51], $color->toArray());
        }

        $colors = [
            Rgb::fromString('#f038'),
            Rgb::fromString('#F038'),
            Rgb::fromString('#ff003388'),
            Rgb::fromString('#FF003388'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51, 136 / 255], $color->toArray());
        }
    }

    public function test_it_is_instantiable_using_functional_notation()
    {
        $colors = [
            Rgb::fromString('rgb(255,0,51)'),
            Rgb::fromString('rgb(100%,0%,20%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51], $color->toArray());
        }

        $colors = [
            Rgb::fromString('rgba(255,0,51,0.7)'),
            Rgb::fromString('rgba(255,0,51,.7)'),
            Rgb::fromString('rgba(255,0,51,70%)'),
            Rgb::fromString('rgba(100%,0%,20%,0.7)'),
            Rgb::fromString('rgba(100%,0%,20%,.7)'),
            Rgb::fromString('rgba(100%,0%,20%,70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51, 0.7], $color->toArray());
        }
    }

    public function test_functional_notation_also_works_with_spacing()
    {
        $colors = [
            Rgb::fromString('rgb(255, 0, 51)'),
            Rgb::fromString('rgb(100%, 0%, 20%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51], $color->toArray());
        }

        $colors = [
            Rgb::fromString('rgba(255, 0, 51, 0.7)'),
            Rgb::fromString('rgba(255, 0, 51, .7)'),
            Rgb::fromString('rgba(255, 0, 51, 70%)'),
            Rgb::fromString('rgba(100%, 0%, 20%, 0.7)'),
            Rgb::fromString('rgba(100%, 0%, 20%, .7)'),
            Rgb::fromString('rgba(100%, 0%, 20%, 70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([255, 0, 51, 0.7], $color->toArray());
        }
    }

    public function test_it_can_create_a_modified_version_of_itself()
    {
        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['blue' => 0]);

        $this->assertEquals([255, 0, 0], $rgbTwo->toArray());

        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0]);

        $this->assertEquals([0, 0, 0], $rgbTwo->toArray());

        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0, 'alpha' => 0.7]);

        $this->assertEquals([0, 0, 0, 0.7], $rgbTwo->toArray());

        $rgb = new Rgb(255, 0, 51, 0.7);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0, 'alpha' => 0]);

        $this->assertEquals([0, 0, 0, 0], $rgbTwo->toArray());
    }

    public function test_it_cant_be_instantiated_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = new Rgb(1, 2);
    }

    public function test_it_cant_be_instantiated_with_non_numeric_values()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = new Rgb(123, 234, 132, 'apples');
    }

    public function test_it_cant_be_instantiated_with_an_unrecognized_keyword()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('fakecolor');
    }

    public function test_it_cant_be_instantiated_from_hex_without_hash()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('f03');
    }

    public function test_it_cant_be_instantiated_from_hex_with_bad_characters()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('#gghhii');
    }

    public function test_it_cant_be_instantiated_from_hex_with_bad_length()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('#af');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_fractional_colors()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('rgb(255, 0, 51.2)');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_mixed_int_and_percentage_colors()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('rgb(100%, 0, 20%)');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_unrecognized_string_format()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('hsl(240, 75%, 20%)');
    }

    public function test_it_cant_be_instantiated_from_rgb_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('rgb(240, 75%)');
    }

    public function test_it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromString('rgb(1,2,3)')->with(['hue' => 120]);
    }

    public function test_converting_to_rgb_provides_same_object()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertSame($rgb, $rgb->toRgb());
    }

    public function test_it_can_convert_to_hsl()
    {
        $rgb = new Rgb(255, 0, 51);
        $hsl = $rgb->toHsl();

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals('hsl(348, 100%, 50%)', $hsl);
        $this->assertFalse($hsl->hasAlpha());

        $rgba = new Rgb(255, 0, 51, 0.7);
        $hsla = $rgba->toHsl();

        $this->assertTrue($hsla->hasAlpha());

        // Shades of gray.
        $rgb = new Rgb(100, 100, 100);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(0, 0%, 39.21569%)', $hsl);
        $this->assertFalse($hsl->hasAlpha());

        $rgba = new Rgb(55, 55, 55, 0.7);
        $hsla = $rgba->toHsl();

        $this->assertTrue($hsla->hasAlpha());

        // Step 5 - lightness under 0.5.
        $rgb = new Rgb(25, 55, 40);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(150, 37.5%, 15.68627%)', $hsl);

        // Step 5 - lightness over 0.5.
        $rgb = new Rgb(100, 125, 150);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(210, 20%, 49.01961%)', $hsl);

        // Step 6 - max == red.
        $rgb = new Rgb(255, 50, 100);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(345.36585, 100%, 59.80392%)', $hsl);

        // Step 6 - max == green.
        $rgb = new Rgb(50, 255, 100);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(134.63415, 100%, 59.80392%)', $hsl);

        // Step 6 - max == blue.
        $rgb = new Rgb(50, 100, 255);
        $hsl = $rgb->toHsl();

        $this->assertEquals('hsl(225.36585, 100%, 59.80392%)', $hsl);
    }
}
