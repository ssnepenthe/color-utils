<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HslTest extends TestCase
{
    /** @test */
    function it_is_instantiable()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertInstanceOf(ColorInterface::class, $hsl);
        $this->assertInstanceOf(Hsl::class, $hsl);
    }

    /** @test */
    function it_rotates_hue_into_a_0_to_360_range()
    {
        $tests = [
            1   => new Hsl(361, 50, 50),
            220 => new Hsl(580, 50, 50),
            330 => new Hsl(-30, 50, 50),
            0   => new Hsl(720, 50, 50),
        ];

        foreach ($tests as $expected => $hsl) {
            $this->assertEquals($expected, $hsl->getHue());
        }
    }

    /** @test */
    function it_forces_a_0_to_100_range_for_saturation_and_lightness()
    {
        $hsl = new Hsl(0, -50, -100);
        $hsl2 = new Hsl(0, 150, 200);

        $this->assertEquals(0, $hsl->getSaturation());
        $this->assertEquals(0, $hsl->getLightness());

        $this->assertEquals(100, $hsl2->getSaturation());
        $this->assertEquals(100, $hsl2->getLightness());
    }

    /** @test */
    function it_can_be_cast_to_a_string()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals('hsl(348, 100%, 50%)', (string) $hsl);
        $this->assertEquals('hsl(348, 100%, 50%)', $hsl->toString());
    }

    /** @test */
    function channel_getters_give_correct_value()
    {
        $hsl = new Hsl(348, 100, 50);

        $this->assertEquals(348, $hsl->getHue());
        $this->assertEquals(100.0, $hsl->getSaturation());
        $this->assertEquals(50.0, $hsl->getLightness());
        $this->assertEquals(1.0, $hsl->getAlpha());
        $this->assertFalse($hsl->hasAlpha());
    }

    /** @test */
    function it_can_tell_lightness()
    {
        $this->assertTrue((new Hsl(38, 100, 51))->isLight());
        $this->assertFalse((new Hsl(274, 100, 25))->isLight());
    }

    /** @test */
    function it_can_tell_lightness_with_custom_threshold()
    {
        $this->assertTrue((new Hsl(60, 100, 50))->isLight(35));
        $this->assertFalse((new Hsl(120, 100, 25))->isLight(35));
    }

    /** @test */
    function it_correctly_produces_hsl_array()
    {
        $this->assertEquals(
            ['hue' => 348, 'saturation' => 100, 'lightness' => 50],
            (new Hsl(348, 100, 50))->toArray()
        );
    }

    /** @test */
    function it_correctly_produces_color_instance()
    {
        $this->assertInstanceOf(
            Color::class,
            (new Hsl(348, 100, 50))->toColor()
        );
    }

    /** @test */
    function it_can_create_a_modified_version_of_itself()
    {
        $hsl = new Hsl(348, 100, 50);
        $hsl2 = $hsl->with(['hue' => 0]);
        $hsl3 = $hsl->with(['hue' => 0, 'saturation' => 0]);

        $this->assertEquals('hsl(0, 100%, 50%)', $hsl2);
        $this->assertEquals('hsl(0, 0%, 50%)', $hsl3);
    }

    /** @test */
    function it_can_create_a_version_of_itself_with_transparency()
    {
        $hsl = new Hsl(348, 100, 50);
        $hsla = $hsl->with(['hue' => 0, 'saturation' => 0, 'alpha' => 0.7]);

        $this->assertInstanceOf(Hsla::class, $hsla);
        $this->assertEquals('hsla(0, 0%, 50%, 0.7)', $hsla);
    }

    /** @test */
    function it_cant_be_instantiated_with_non_numeric_values()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsl(123, 75, 'apples');
    }

    /** @test */
    function it_cant_create_a_new_instance_without_valid_attrs()
    {
        $this->expectException(InvalidArgumentException::class);

        (new Hsl(123, 75, 50))->with(['red' => 50]);
    }
}
