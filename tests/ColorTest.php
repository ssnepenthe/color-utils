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
        $color = Color::fromString('#ff0033');

        $this->assertInstanceOf(Color::class, $color);
    }

    public function test_it_can_be_instantiated_from_hsl()
    {
        $tests = [
            new Color(new Hsl(348, 100, 50)),
            Color::fromHsl(348, 100, 50),
            Color::fromString('hsl(348, 100%, 50%)'),
        ];

        foreach ($tests as $test) {
            $this->assertInstanceOf(Color::class, $test);
            $this->assertEquals([348, 100, 50], $test->toArray());
        }
    }

    public function test_it_can_be_instantiated_from_keyword()
    {
        $color = Color::fromString('goldenrod');

        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([218, 165, 32], $color->toArray());
    }

    public function test_it_can_be_instantiated_from_rgb()
    {
        $tests = [
            new Color(new Rgb(255, 0, 51)),
            Color::fromRgb(255, 0, 51),
            Color::fromString('rgb(255, 0, 51)'),
        ];

        foreach ($tests as $test) {
            $this->assertInstanceOf(Color::class, $test);
            $this->assertEquals([255, 0, 51], $test->toArray());
        }
    }

    public function test_it_gives_an_rgb_instance()
    {
        $this->assertInstanceOf(Rgb::class, Color::fromRgb(255, 0, 51)->getRgb());
    }

    public function test_it_gives_the_correct_rgb_values()
    {
        $color = Color::fromRgb(255, 0, 51);

        $this->assertEquals([255, 0, 51], $color->toArray());

        $this->assertFalse($color->hasAlpha());
        $this->assertEquals(1.0, $color->getAlpha());
        $this->assertEquals('', $color->getName());

        $color = Color::fromRgb(255, 0, 51, 0.7);

        $this->assertEquals([255, 0, 51, 0.7], $color->toArray());

        $this->assertTrue($color->hasAlpha());
        $this->assertEquals('', $color->getName());
    }

    public function test_it_gives_an_hsl_instance()
    {
        $this->assertInstanceOf(Hsl::class, Color::fromHsl(348, 100, 50)->getHsl());
    }

    public function test_it_gives_the_correct_hsl_values()
    {
        $color = Color::fromHsl(348, 100, 50);

        $this->assertEquals([348, 100, 50], $color->toArray());

        $this->assertFalse($color->hasAlpha());
        $this->assertEquals(1.0, $color->getAlpha());

        $color = Color::fromHsl(348, 100, 50, 0.7);

        $this->assertEquals([348, 100, 50, 0.7], $color->toArray());

        $this->assertTrue($color->hasAlpha());
        $this->assertEquals(0.7, $color->getAlpha());
    }

    public function test_it_can_calculate_perceived_brightness()
    {
        $tests = [
            'black'  => 0,
            'gray'   => 50,
            'white'  => 99, // Formula gives 99, should it be 100?
            'red'    => 54,
            'orange' => 73,
            'yellow' => 94,
            'green'  => 38,
            'blue'   => 33,
            'indigo' => 23,
            'violet' => 71,
        ];

        foreach ($tests as $keyword => $brightness) {
            $this->assertEquals(
                $brightness,
                Color::fromString($keyword)->getPerceivedBrightness()
            );
        }
    }

    public function test_it_gives_the_correct_type_value()
    {
        $this->assertEquals('hsl', Color::fromHsl(348, 100, 50)->getType());
        $this->assertEquals('rgb', Color::fromRgb(255, 0, 51)->getType());
    }

    public function test_it_can_tell_lightness()
    {
        $this->assertTrue(Color::fromString('orange')->isLight());
        $this->assertFalse(Color::fromString('indigo')->isLight());

        $this->assertTrue(Color::fromString('green')->isDark());
        $this->assertFalse(Color::fromString('red')->isDark());
    }

    public function test_it_can_tell_lightness_with_custom_threshold()
    {
        $this->assertTrue(Color::fromString('yellow')->isLight(35));
        $this->assertFalse(Color::fromString('green')->isLight(35));

        $this->assertTrue(Color::fromString('indigo')->isDark(35));
        $this->assertFalse(Color::fromString('violet')->isDark(35));
    }

    public function test_it_can_tell_perceived_brightness()
    {
        $this->assertTrue(Color::fromString('orange')->looksLight());
        $this->assertFalse(Color::fromString('blue')->looksLight());

        $this->assertTrue(Color::fromString('green')->looksDark());
        $this->assertFalse(Color::fromString('red')->looksDark());
    }

    public function test_it_can_tell_perceived_brightness_with_custom_threshold()
    {
        $this->assertTrue(Color::fromString('yellow')->looksLight(35));
        $this->assertFalse(Color::fromString('blue')->looksLight(35));

        $this->assertTrue(Color::fromString('indigo')->looksDark(35));
        $this->assertFalse(Color::fromString('violet')->looksDark(35));
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
        $white = Color::fromString('yellow')->with(['blue' => 255]);

        $this->assertEquals([255, 255, 255], $white->toArray());
    }

    public function test_it_can_modify_hsl_to_create_a_new_color()
    {
        $blue = Color::fromHsl(348, 100, 50)->with(['hue' => 240]);

        $this->assertEquals([240, 100, 50], $blue->toArray());
    }

    public function test_it_can_modify_rgb_to_create_a_new_color()
    {
        $orange = Color::fromString('yellow')->with(['green' => 165]);

        $this->assertEquals([255, 165, 0], $orange->toArray());
    }

    public function test_it_can_modify_alpha_to_create_a_new_color()
    {
        $transparent = Color::fromString('red')->with(['alpha' => 0]);

        $this->assertEquals([255, 0, 0, 0], $transparent->toArray());
    }

    public function test_it_can_modify_alpha_and_other_values_at_once()
    {
        $transparent = Color::fromString('red')->with([
            'alpha' => 0.5,
            'blue' => 255
        ]);

        $this->assertEquals([255, 0, 255, 0.5], $transparent->toArray());

        $transparent = Color::fromHsl(348, 100, 50)->with([
            'alpha' => 0.5,
            'hue' => 270
        ]);

        $this->assertEquals([270, 100, 50, 0.5], $transparent->toArray());
    }

    public function test_it_can_modify_multiple_rgb_attributes_at_once()
    {
        $white = Color::fromString('black')
            ->with(['red' => 255, 'green' => 255, 'blue' => 255]);

        $this->assertEquals([255, 255, 255], $white->toArray());
    }

    public function test_it_can_modify_multiple_hsl_attributes_at_once()
    {
        $white = Color::fromHsl(0, 0, 0)
            ->with(['hue' => 0, 'saturation' => 0, 'lightness' => 100]);

        $this->assertEquals([0, 0, 100], $white->toArray());
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

        Color::fromString('yellow')->with(['blue' => 255, 'hue' => 360]);
    }

    public function test_it_cant_create_a_new_color_without_any_changes()
    {
        $this->expectException(InvalidArgumentException::class);

        Color::fromString('yellow')->with(['not' => 'real']);
    }
}
