<?php

use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\ColorInterface;

class RgbTest extends PHPUnit_Framework_TestCase
{
    public function test_it_implements_color_interface()
    {
        $rgb = new Rgb(255, 0, 51);
        $this->assertInstanceOf(ColorInterface::class, $rgb);
    }

    public function test_it_is_instantiable()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(51, $rgb->getBlue());

        $rgb = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(51, $rgb->getBlue());
        $this->assertEquals(0.7, $rgb->getAlpha());
    }

    public function test_it_can_be_cast_to_a_string()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals('rgb(255, 0, 51)', (string) $rgb);

        $rgb = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals('rgba(255, 0, 51, 0.7)', (string) $rgb);
    }

    public function test_it_gives_the_correct_alpha_value()
    {
        $rgb = new Rgb(255, 0, 51);

        $this->assertEquals(1.0, $rgb->getAlpha());
        $this->assertEquals('ff', $rgb->getAlphaByte());

        $rgb = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals(0.7, $rgb->getAlpha());
        $this->assertEquals('b2', $rgb->getAlphaByte());
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
        $rgb = Rgb::fromHexString('#ff0033');

        $this->assertEquals('', $rgb->getName());

        $rgb = Rgb::fromHexString('#7fffd4');

        $this->assertEquals('aquamarine', $rgb->getName());

        $rgb = Rgb::fromHexString('#b22222');

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
    }

    public function test_it_correct_pads_hex_bytes()
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

        $rgb = new Rgb(255, 0, 51, 0.7);

        $this->assertEquals('rgba(255, 0, 51, 0.7)', $rgb->toString());
    }

    public function test_it_forces_a_0_to_255_range_for_colors()
    {
        $rgb = new Rgb(-1, -50, -100);

        $this->assertEquals(0, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(0, $rgb->getBlue());

        $rgb = new Rgb(256, 300, 350);

        $this->assertEquals(255, $rgb->getRed());
        $this->assertEquals(255, $rgb->getGreen());
        $this->assertEquals(255, $rgb->getBlue());
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
        $rgb = Rgb::fromKeyword('black');

        $this->assertEquals(0, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(0, $rgb->getBlue());

        $rgb = Rgb::fromKeyword('transparent');

        $this->assertEquals(0, $rgb->getRed());
        $this->assertEquals(0, $rgb->getGreen());
        $this->assertEquals(0, $rgb->getBlue());
        $this->assertEquals(0.0, $rgb->getAlpha());
    }

    public function test_it_is_instantiable_using_hex_notation()
    {
        $colors = [
            Rgb::fromHexString('#f03'),
            Rgb::fromHexString('#F03'),
            Rgb::fromHexString('#ff0033'),
            Rgb::fromHexString('#FF0033'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
        }

        $colors = [
            Rgb::fromHexString('#f038'),
            Rgb::fromHexString('#F038'),
            Rgb::fromHexString('#ff003388'),
            Rgb::fromHexString('#FF003388'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(136 / 255, $color->getAlpha());
        }
    }

    public function test_it_is_instantiable_using_int_hex_representation()
    {
        $colors = [
            Rgb::fromInt(0xff0033),
            Rgb::fromInt(16711731),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
        }

        $colors = [
            Rgb::fromInt(0xff003388),
            Rgb::fromInt(4278203272),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(136 / 255, $color->getAlpha());
        }
    }

    public function test_it_is_instantiable_using_functional_notation()
    {
        $colors = [
            Rgb::fromRgbString('rgb(255,0,51)'),
            Rgb::fromRgbString('rgb(100%,0%,20%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
        }

        $colors = [
            Rgb::fromRgbString('rgba(255,0,51,0.7)'),
            Rgb::fromRgbString('rgba(255,0,51,.7)'),
            Rgb::fromRgbString('rgba(255,0,51,70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(0.7, $color->getAlpha());
        }

        $colors = [
            Rgb::fromRgbString('rgba(100%,0%,20%,0.7)'),
            Rgb::fromRgbString('rgba(100%,0%,20%,.7)'),
            Rgb::fromRgbString('rgba(100%,0%,20%,70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(0.7, $color->getAlpha());
        }
    }

    public function test_functional_notation_also_works_with_spacing()
    {
        $colors = [
            Rgb::fromRgbString('rgb(255, 0, 51)'),
            Rgb::fromRgbString('rgb(100%, 0%, 20%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
        }

        $colors = [
            Rgb::fromRgbString('rgba(255, 0, 51, 0.7)'),
            Rgb::fromRgbString('rgba(255, 0, 51, .7)'),
            Rgb::fromRgbString('rgba(255, 0, 51, 70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(0.7, $color->getAlpha());
        }

        $colors = [
            Rgb::fromRgbString('rgba(100%, 0%, 20%, 0.7)'),
            Rgb::fromRgbString('rgba(100%, 0%, 20%, .7)'),
            Rgb::fromRgbString('rgba(100%, 0%, 20%, 70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(255, $color->getRed());
            $this->assertEquals(0, $color->getGreen());
            $this->assertEquals(51, $color->getBlue());
            $this->assertEquals(0.7, $color->getAlpha());
        }
    }

    public function test_it_can_create_a_modified_version_of_itself()
    {
        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['blue' => 0]);

        $this->assertEquals(255, $rgbTwo->getRed());
        $this->assertEquals(0, $rgbTwo->getGreen());
        $this->assertEquals(0, $rgbTwo->getBlue());

        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0]);

        $this->assertEquals(0, $rgbTwo->getRed());
        $this->assertEquals(0, $rgbTwo->getGreen());
        $this->assertEquals(0, $rgbTwo->getBlue());

        $rgb = new Rgb(255, 0, 51);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0, 'alpha' => 0.7]);

        $this->assertEquals(0, $rgbTwo->getRed());
        $this->assertEquals(0, $rgbTwo->getGreen());
        $this->assertEquals(0, $rgbTwo->getBlue());
        $this->assertEquals(0.7, $rgbTwo->getAlpha());

        $rgb = new Rgb(255, 0, 51, 0.7);
        $rgbTwo = $rgb->with(['red' => 0, 'blue' => 0, 'alpha' => 0]);

        $this->assertEquals(0, $rgbTwo->getRed());
        $this->assertEquals(0, $rgbTwo->getGreen());
        $this->assertEquals(0, $rgbTwo->getBlue());
        $this->assertEquals(0, $rgbTwo->getAlpha());
    }

    public function test_it_cant_be_instantiated_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = new Rgb(1, 2);
    }

    public function test_it_cant_be_instantiated_with_a_non_numeric_alpha()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = new Rgb(123, 234, 132, 'apples');
    }

    public function test_it_cant_be_instantiated_with_an_unrecognized_keyword()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromKeyword('fakecolor');
    }

    public function test_it_cant_be_instantiated_from_hex_without_hash()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromHexString('f03');
    }

    public function test_it_cant_be_instantiated_from_hex_with_bad_characters()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromHexString('#gghhii');
    }

    public function test_it_cant_be_instantiated_from_hex_with_bad_length()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromHexString('#af');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_fractional_colors()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromRgbString('rgb(255, 0, 51.2)');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_mixed_int_and_percentage_colors()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromRgbString('rgb(100%, 0, 20%)');
    }

    public function test_it_cant_be_instantiated_from_rgb_with_unrecognized_string_format()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromRgbString('hsl(240, 75%, 20%)');
    }

    public function test_it_cant_be_instantiated_from_rgb_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromRgbString('rgb(240, 75%)');
    }

    public function test_it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        $rgb = Rgb::fromRgbString('rgb(1,2,3)')->with(['hue' => 120]);
    }
}
