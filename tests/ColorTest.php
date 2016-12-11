<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class ColorTest extends PHPUnit_Framework_TestCase
{
    public function test_it_implements_color_interface()
    {
        $color = new Color(new Rgb(255, 0, 51));

        $this->assertInstanceOf(ColorInterface::class, $color);
    }

    public function test_it_is_instantiable()
    {
        $color = new Color(new Rgb(255, 0, 51));

        $this->assertInstanceOf(Color::class, $color);
    }

    public function test_it_can_be_cast_to_string()
    {
        $this->assertEquals('rgb(255, 0, 51)', (string) Color::fromRgb(255, 0, 51));
        $this->assertEquals(
            'hsl(348, 100%, 50%)',
            (string) Color::fromHsl(348, 100, 50)
        );
    }

    public function test_it_can_be_instantiated_from_hex()
    {
        $color = Color::fromHex('#ff0033');

        $this->assertInstanceOf(Color::class, $color);
    }

    public function test_it_can_be_instantiated_from_hsl()
    {
        $tests = [
            Color::fromHsl(348, 100, 50),
            Color::fromHsl(348, 100, 50, 0.7),
            Color::fromHsl('hsl(348, 100%, 50%)'),
            Color::fromHsl('hsl(348, 100%, 50%, 0.7)'),
        ];

        foreach ($tests as $test) {
            $this->assertInstanceOf(Color::class, $test);
        }
    }

    public function test_it_can_be_instantiated_from_keyword()
    {
        $color = Color::fromKeyword('goldenrod');

        $this->assertInstanceOf(Color::class, $color);
    }

    public function test_it_can_be_instantiated_from_rgb()
    {
        $tests = [
            Color::fromRgb(255, 0, 51),
            Color::fromRgb(255, 0, 51, 0.7),
            Color::fromRgb('rgb(255, 0, 51)'),
            Color::fromRgb('rgb(255, 0, 51, 0.7)'),
        ];

        foreach ($tests as $test) {
            $this->assertInstanceOf(Color::class, $test);
        }
    }

    public function test_it_gives_an_rgb_instance()
    {
        $this->assertInstanceOf(Rgb::class, Color::fromRgb(255, 0, 51)->getRgb());
    }

    public function test_it_gives_the_correct_rgb_values()
    {
        $color = Color::fromRgb(255, 0, 51);

        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(51, $color->getBlue());

        $this->assertFalse($color->hasAlpha());
        $this->assertEquals(1, $color->getAlpha());

        $this->assertEquals('', $color->getName());

        $color = Color::fromRgb(255, 0, 51, 0.7);

        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(51, $color->getBlue());

        $this->assertTrue($color->hasAlpha());
        $this->assertEquals(0.7, $color->getAlpha());

        $this->assertEquals('', $color->getName());
    }

    public function test_it_gives_an_hsl_instance()
    {
        $this->assertInstanceOf(Hsl::class, Color::fromHsl(348, 100, 50)->getHsl());
    }

    public function test_it_gives_the_correct_hsl_values()
    {
        $color = Color::fromHsl(348, 100, 50);

        $this->assertEquals(348, $color->getHue());
        $this->assertEquals(100, $color->getSaturation());
        $this->assertEquals(50, $color->getLightness());

        $this->assertFalse($color->hasAlpha());
        $this->assertEquals(1.0, $color->getAlpha());

        $color = Color::fromHsl(348, 100, 50, 0.7);

        $this->assertEquals(348, $color->getHue());
        $this->assertEquals(100, $color->getSaturation());
        $this->assertEquals(50, $color->getLightness());

        $this->assertTrue($color->hasAlpha());
        $this->assertEquals(0.7, $color->getAlpha());
    }

    public function test_it_can_calculate_perceived_brightness()
    {
        $this->assertEquals(
            0,
            Color::fromKeyword('black')->getPerceivedBrightness()
        );
        $this->assertEquals(
            50,
            Color::fromKeyword('gray')->getPerceivedBrightness()
        );
        // Formula gives 99, but should this technically be 100?
        $this->assertEquals(
            99,
            Color::fromKeyword('white')->getPerceivedBrightness()
        );
        $this->assertEquals(
            54,
            Color::fromKeyword('red')->getPerceivedBrightness()
        );
        $this->assertEquals(
            73,
            Color::fromKeyword('orange')->getPerceivedBrightness()
        );
        $this->assertEquals(
            94,
            Color::fromKeyword('yellow')->getPerceivedBrightness()
        );
        $this->assertEquals(
            38,
            Color::fromKeyword('green')->getPerceivedBrightness()
        );
        $this->assertEquals(
            33,
            Color::fromKeyword('blue')->getPerceivedBrightness()
        );
        $this->assertEquals(
            23,
            Color::fromKeyword('indigo')->getPerceivedBrightness()
        );
        $this->assertEquals(
            71,
            Color::fromKeyword('violet')->getPerceivedBrightness()
        );
    }

    public function test_it_gives_the_correct_type_value()
    {
        $this->assertEquals('hsl', Color::fromHsl(348, 100, 50)->getType());
        $this->assertEquals('rgb', Color::fromRgb(255, 0, 51)->getType());
    }

    public function test_it_can_tell_lightness()
    {
        $this->assertTrue(Color::fromKeyword('orange')->isLight());
        $this->assertFalse(Color::fromKeyword('indigo')->isLight());

        $this->assertTrue(Color::fromKeyword('green')->isDark());
        $this->assertFalse(Color::fromKeyword('red')->isDark());
    }

    public function test_it_can_tell_lightness_with_custom_threshold()
    {
        $this->assertTrue(Color::fromKeyword('yellow')->isLight(35));
        $this->assertFalse(Color::fromKeyword('green')->isLight(35));

        $this->assertTrue(Color::fromKeyword('indigo')->isDark(35));
        $this->assertFalse(Color::fromKeyword('violet')->isDark(35));
    }

    public function test_it_can_tell_perceived_brightness()
    {
        $this->assertTrue(Color::fromKeyword('orange')->looksLight());
        $this->assertFalse(Color::fromKeyword('blue')->looksLight());

        $this->assertTrue(Color::fromKeyword('green')->looksDark());
        $this->assertFalse(Color::fromKeyword('red')->looksDark());
    }

    public function test_it_can_tell_perceived_brightness_with_custom_threshold()
    {
        $this->assertTrue(Color::fromKeyword('yellow')->looksLight(35));
        $this->assertFalse(Color::fromKeyword('blue')->looksLight(35));

        $this->assertTrue(Color::fromKeyword('indigo')->looksDark(35));
        $this->assertFalse(Color::fromKeyword('violet')->looksDark(35));
    }

    public function test_it_can_set_type()
    {
        $color = Color::fromRgb(255, 0, 51);

        $this->assertEquals('rgb', $color->getType());

        $color->setType('hsl');

        $this->assertEquals('hsl', $color->getType());
    }

    public function test_it_can_only_set_hsl_and_rgb_as_type()
    {
        $color = Color::fromRgb(255, 0, 51)->setType('badtype');

        $this->assertEquals('rgb', $color->getType());
    }

    public function test_it_can_correctly_be_converted_to_an_array()
    {
        $this->assertEquals([255, 0, 51], Color::fromRgb(255, 0, 51)->toArray());
        $this->assertEquals([348, 100, 50], Color::fromHsl(348, 100, 50)->toArray());
    }

    public function test_it_can_correctly_be_converted_to_a_string()
    {
        $this->assertEquals(
            'rgb(255, 0, 51)',
            Color::fromRgb(255, 0, 51)->toString()
        );
        $this->assertEquals(
            'hsl(348, 100%, 50%)',
            Color::fromHsl(348, 100, 50)->toString()
        );
    }

    public function test_it_can_create_a_modified_version_of_itself()
    {
        $white = Color::fromKeyword('yellow')->with(['blue' => 255]);

        $this->assertEquals(255, $white->getRed());
        $this->assertEquals(255, $white->getGreen());
        $this->assertEquals(255, $white->getBlue());
    }

    public function test_it_can_modify_hsl_to_create_a_new_color()
    {
        $blue = Color::fromHsl(348, 100, 50)->with(['hue' => 240]);

        $this->assertEquals(240, $blue->getHue());
        $this->assertEquals(100, $blue->getSaturation());
        $this->assertEquals(50, $blue->getLightness());
    }

    public function test_it_can_modify_rgb_to_create_a_new_color()
    {
        $orange = Color::fromKeyword('yellow')->with(['green' => 165]);

        $this->assertEquals(255, $orange->getRed());
        $this->assertEquals(165, $orange->getGreen());
        $this->assertEquals(0, $orange->getBlue());
    }

    public function test_it_can_modify_multiple_rgb_attributes_at_once()
    {
        $white = Color::fromKeyword('black')
            ->with(['red' => 255, 'green' => 255, 'blue' => 255]);

        $this->assertEquals(255, $white->getRed());
        $this->assertEquals(255, $white->getGreen());
        $this->assertEquals(255, $white->getBlue());
    }

    public function test_it_can_modify_multiple_hsl_attributes_at_once()
    {
        $white = Color::fromHsl(0, 0, 0)
            ->with(['hue' => 0, 'saturation' => 0, 'lightness' => 100]);

        $this->assertEquals(0, $white->getHue());
        $this->assertEquals(0, $white->getSaturation());
        $this->assertEquals(100, $white->getLightness());
    }

    public function test_it_creates_a_new_color_with_the_same_type_as_original()
    {
        $hsl = Color::fromHsl(60, 100, 50);
        $rgb = Color::fromRgb(255, 255, 0);

        foreach ([$hsl, $rgb] as $yellow) {
            $lime = $yellow->with(['red' => 0]);

            $this->assertEquals($yellow->getType(), $lime->getType());
        }
    }

    public function test_it_cant_modify_hsl_and_rgb_in_same_operation()
    {
        $this->expectException(InvalidArgumentException::class);

        Color::fromKeyword('yellow')->with(['blue' => 255, 'hue' => 360]);
    }

    public function test_it_cant_create_a_new_color_without_any_changes()
    {
        $this->expectException(InvalidArgumentException::class);

        Color::fromKeyword('yellow')->with(['not' => 'real']);
    }
}
