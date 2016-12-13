<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\ColorInterface;

class HslTest extends PHPUnit_Framework_TestCase
{
    public function test_it_implements_color_interface()
    {
        $hsl = new Hsl(348, 100, 50);
        $this->assertInstanceOf(ColorInterface::class, $hsl);
    }

    public function test_all_object_properties_are_stored_as_correct_type()
    {
        $colors = [
            new Hsl(348, 100, 50),
            new Hsl(348, 100, 50, 1),
        ];

        foreach ($colors as $color) {
            $this->assertAttributeInternalType('float', 'hue', $color);
            $this->assertAttributeInternalType('float', 'saturation', $color);
            $this->assertAttributeInternalType('float', 'lightness', $color);
            $this->assertAttributeInternalType('float', 'alpha', $color);
        }
    }

    public function test_it_is_instantiable()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals([348, 100, 50], $hsl->toArray());

        $hsl = new Hsl(348, 100, 50, 0.7);

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals([348, 100, 50, 0.7], $hsl->toArray());
    }

    public function test_it_can_be_cast_to_a_string()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals('hsl(348, 100%, 50%)', (string) $hsl);

        $hsla = new Hsl(348, 100, 50, 0.7);

        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', (string) $hsla);
    }

    public function test_it_gives_the_correct_alpha_value()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertFalse($hsl->hasAlpha());
        $this->assertEquals(1.0, $hsl->getAlpha());

        $hsl = new Hsl(348, 100, 50, 0.7);

        $this->assertTrue($hsl->hasAlpha());
        $this->assertEquals(0.7, $hsl->getAlpha());
    }

    public function test_it_gives_the_correct_hue_value()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals(348, $hsl->getHue());
    }

    public function test_it_gives_the_correct_lightness_value()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals(50, $hsl->getLightness());
    }

    public function test_it_gives_the_correct_saturation_value()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals(100, $hsl->getSaturation());
    }

    public function test_it_correctly_produces_hsl_array()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals([348, 100, 50], $hsl->toArray());

        $hsl = new Hsl(348, 100, 50, 0.7);

        $this->assertEquals([348, 100, 50, 0.7], $hsl->toArray());
    }

    public function test_it_correctly_produces_hsl_string()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals('hsl(348, 100%, 50%)', $hsl->toString());

        $hsl = new Hsl(348, 100, 50, 0.7);

        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', $hsl->toString());
    }

    public function test_it_correctly_produces_color_instance()
    {
        $this->assertInstanceOf(
            Color::class,
            Hsl::fromString('hsl(348, 100%, 50%)')->toColor()
        );
    }

    public function test_it_forces_a_0_to_1_range_for_alpha()
    {
        $hsl = new Hsl(0, 0, 0, -1);

        $this->assertEquals(0, $hsl->getAlpha());

        $hsl = new Hsl(0, 0, 0, 2);

        $this->assertEquals(1, $hsl->getAlpha());
    }

    public function test_it_rotates_hue_into_a_0_to_360_range()
    {
        $tests = [
            1 => new Hsl(361, 50, 50),
            220 => new Hsl(580, 50, 50),
            330 => new Hsl(-30, 50, 50),
            360 => new Hsl(720, 50, 50),
        ];

        foreach ($tests as $expected => $hsl) {
            $this->assertEquals($expected, $hsl->getHue());
        }
    }

    public function test_it_forces_a_0_to_100_range_for_saturation_and_lightness()
    {
        $hsl = new Hsl(0, -50, -100);

        $this->assertEquals(0, $hsl->getSaturation());
        $this->assertEquals(0, $hsl->getLightness());

        $hsl = new Hsl(0, 150, 200);

        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(100, $hsl->getLightness());
    }

    public function test_it_is_instantiable_using_functional_notation()
    {
        $color = Hsl::fromString('hsl(348,100%,50%)');

        $this->assertEquals([348, 100, 50], $color->toArray());

        $colors = [
            Hsl::fromString('hsla(348,100%,50%,0.7)'),
            Hsl::fromString('hsla(348,100%,50%,.7)'),
            Hsl::fromString('hsla(348,100%,50%,70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals([348, 100, 50, 0.7], $color->toArray());
        }
    }

    public function test_functional_notation_also_works_with_spaces()
    {
        $hsl = Hsl::fromString('hsl(348, 100%, 50%)');

        $this->assertEquals([348, 100, 50], $hsl->toArray());

        $hsla = Hsl::fromString('hsla(348, 100%, 50%, 0.7)');

        $this->assertEquals([348, 100, 50, 0.7], $hsla->toArray());
    }

    public function test_it_can_create_a_modified_version_of_itself()
    {
        $hsl = new Hsl(348, 100, 50);
        $hslTwo = $hsl->with(['hue' => 0]);

        $this->assertEquals([0, 100, 50], $hslTwo->toArray());

        $hslTwo = $hsl->with(['hue' => 0, 'saturation' => 0]);

        $this->assertEquals([0, 0, 50], $hslTwo->toArray());

        $hslTwo = $hsl->with(['hue' => 0, 'saturation' => 0, 'alpha' => 0.7]);

        $this->assertEquals([0, 0, 50, 0.7], $hslTwo->toArray());

        $hsla = new Hsl(348, 100, 50, 0.7);
        $hslTwo = $hsla->with([
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 0,
        ]);

        $this->assertEquals([0, 0, 0, 0], $hslTwo->toArray());
    }

    public function test_it_cant_be_instantiated_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsl(123, 75);
    }

    public function test_it_cant_be_instantiated_with_non_numeric_values()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsl(123, 75, 50, 'apples');
    }

    public function test_it_cant_be_instantiated_with_an_unrecognized_string_format()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('rgb(123,234,345)');
    }

    public function test_it_cant_be_instantiated_with_non_numeric_characters()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(ff,aa,cc)');
    }

    public function test_it_cant_be_instantiated_from_string_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(123,75)');
    }

    public function test_it_cant_be_instantiated_with_percentage_hue()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(123%,75%,50%)');
    }

    public function test_it_cant_be_instantiated_unless_given_percentage_saturation()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(123,75,50%)');
    }

    public function test_it_cant_be_instantiated_unless_given_percentage_lightness()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(123,75%,50)');
    }

    public function test_it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromString('hsl(123,75%,50%)')->with(['red' => 50]);
    }
}
