<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\ColorInterface;

class HslTest extends PHPUnit_Framework_TestCase
{
    public function test_it_implements_color_interface()
    {
        $hsl = new Hsl(348, 100, 50);
        $this->assertInstanceOf(ColorInterface::class, $hsl);
    }

    public function test_it_is_instantiable()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(348, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(50, $hsl->getLightness());

        $hsl = new Hsl(348, 100, 50, 0.7);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(348, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(50, $hsl->getLightness());
        $this->assertEquals(0.7, $hsl->getAlpha());
    }

    public function test_it_is_instantiable_using_functional_notation()
    {
        $color = Hsl::fromHslString('hsl(348,100%,50%)');

        $this->assertEquals(348, $color->getHue());
        $this->assertEquals(100, $color->getSaturation());
        $this->assertEquals(50, $color->getLightness());

        $colors = [
            Hsl::fromHslString('hsla(348,100%,50%,0.7)'),
            Hsl::fromHslString('hsla(348,100%,50%,.7)'),
            Hsl::fromHslString('hsla(348,100%,50%,70%)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals(348, $color->getHue());
            $this->assertEquals(100, $color->getSaturation());
            $this->assertEquals(50, $color->getLightness());
            $this->assertEquals(0.7, $color->getAlpha());
        }
    }

    public function test_functional_notation_also_works_with_spaces()
    {
        $hsl = Hsl::fromHslString('hsl(348, 100%, 50%)');

        $this->assertEquals(348, $hsl->getHue());
        $this->assertEquals(100, $hsl->getSaturation());
        $this->assertEquals(50, $hsl->getLightness());

        $hsla = Hsl::fromHslString('hsla(348, 100%, 50%, 0.7)');

        $this->assertEquals(348, $hsla->getHue());
        $this->assertEquals(100, $hsla->getSaturation());
        $this->assertEquals(50, $hsla->getLightness());
        $this->assertEquals(0.7, $hsla->getAlpha());
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

    public function test_it_can_create_a_modified_version_of_itself()
    {
        $hsl = new Hsl(348, 100, 50);
        $hslTwo = $hsl->with(['hue' => 0]);

        $this->assertEquals(0, $hslTwo->getHue());
        $this->assertEquals(100, $hslTwo->getSaturation());
        $this->assertEquals(50, $hslTwo->getLightness());

        $hslTwo = $hsl->with(['hue' => 0, 'saturation' => 0]);

        $this->assertEquals(0, $hslTwo->getHue());
        $this->assertEquals(0, $hslTwo->getSaturation());
        $this->assertEquals(50, $hslTwo->getLightness());

        $hslTwo = $hsl->with(['hue' => 0, 'saturation' => 0, 'alpha' => 0.7]);

        $this->assertEquals(0, $hslTwo->getHue());
        $this->assertEquals(0, $hslTwo->getSaturation());
        $this->assertEquals(50, $hslTwo->getLightness());
        $this->assertEquals(0.7, $hslTwo->getAlpha());

        $hsla = new Hsl(348, 100, 50, 0.7);
        $hslTwo = $hsla->with([
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 0,
        ]);

        $this->assertEquals(0, $hslTwo->getHue());
        $this->assertEquals(0, $hslTwo->getSaturation());
        $this->assertEquals(0, $hslTwo->getLightness());
        $this->assertEquals(0, $hslTwo->getAlpha());
    }

    public function test_it_cant_be_instantiated_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsl(123, 75);
    }

    public function test_it_cant_be_instantiated_with_a_non_numeric_alpha()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsl(123, 75, 50, 'apples');
    }

    public function test_it_cant_be_instantiated_with_an_unrecognized_string_format()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('rgb(123,234,345)');
    }

    public function test_it_cant_be_instantiated_with_non_numeric_characters()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(ff,aa,cc)');
    }

    public function test_it_cant_be_instantiated_from_string_unless_given_3_or_4_args()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(123,75)');
    }

    public function test_it_cant_be_instantiated_with_percentage_hue()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(123%,75%,50%)');
    }

    public function test_it_cant_be_instantiated_unless_given_percentage_saturation()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(123,75,50%)');
    }

    public function test_it_cant_be_instantiated_unless_given_percentage_lightness()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(123,75%,50)');
    }

    public function test_it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        Hsl::fromHslString('hsl(123,75%,50%)')->with(['red' => 50]);
    }
}
