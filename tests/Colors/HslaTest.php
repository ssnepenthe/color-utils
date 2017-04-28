<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HslaTest extends TestCase
{
    /** @test */
    function it_is_instantiable()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);

        $this->assertInstanceOf(ColorInterface::class, $hsla);
        $this->assertInstanceOf(Hsla::class, $hsla);
    }

    /** @test */
    function it_forces_a_0_to_1_range_for_alpha()
    {
        $hsla = new Hsla(0, 0, 0, -0.1);
        $hsla2 = new Hsla(0, 0, 0, 1.1);

        $this->assertEquals('hsla(0, 0%, 0%, 0)', $hsla);
        $this->assertEquals('hsl(0, 0%, 0%)', $hsla2);
    }

    /** @test */
    function it_can_be_cast_to_a_string()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);

        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', (string) $hsla);
        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', $hsla->toString());
    }

    /** @test */
    function it_correctly_formats_alpha_channel_in_string_conversion()
    {
        /*

        FORMATTING RULES:

        Alpha is dropped if == 1.0.
        All trailing "0" characters are trimmed.
        "." character is removed if there is nothing to the right of it.

         */
        $map = [
            'hsl(348, 100%, 50%)'        => new Hsla(348, 100, 50, 1.0),
            'hsla(348, 100%, 50%, 0)'    => new Hsla(348, 100, 50, 0.0),
            'hsla(348, 100%, 50%, 0.12)' => new Hsla(348, 100, 50, 0.123456),
            'hsla(348, 100%, 50%, 0.1)'  => new Hsla(348, 100, 50, 0.101),
        ];

        foreach ($map as $string => $hsla) {
            $this->assertEquals($string, $hsla->toString());
        }
    }

    /** @test */
    function channel_getters_give_correct_value()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);

        $this->assertEquals(0.7, $hsla->getAlpha());
        $this->assertTrue($hsla->hasAlpha());
    }

    /** @test */
    function it_correctly_produces_hsl_array()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);
        $hsla2 = new Hsla(348, 100, 50, 1);

        $this->assertEquals(
            ['hue' => 348, 'saturation' => 100, 'lightness' => 50, 'alpha' => 0.7],
            $hsla->toArray()
        );
        $this->assertEquals(
            ['hue' => 348, 'saturation' => 100, 'lightness' => 50],
            $hsla2->toArray()
        );
    }

    /** @test */
    function it_correctly_produces_color_instance()
    {
        $this->assertInstanceOf(
            Color::class,
            (new Hsla(348, 100, 50, 0.7))->toColor()
        );
    }

    /** @test */
    function it_can_create_a_modified_version_of_itself()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);
        $hsla2 = $hsla->with(['hue' => 0]);
        $hsla3 = $hsla->with(['hue' => 0, 'saturation' => 0]);

        $this->assertEquals('hsla(0, 100%, 50%, 0.7)', $hsla2);
        $this->assertEquals('hsla(0, 0%, 50%, 0.7)', $hsla3);
    }

    /** @test */
    function it_can_create_a_version_of_itself_without_transparency()
    {
        $hsla = new Hsla(348, 100, 50, 0.7);
        $hsl = $hsla->with(['hue' => 0, 'saturation' => 0, 'alpha' => 1]);

        // Should be Hsl instance.
        $this->assertNotInstanceOf(Hsla::class, $hsl);
        $this->assertEquals('hsl(0, 0%, 50%)', $hsl);
    }

    /** @test */
    function it_cant_be_instantiated_with_non_numeric_values()
    {
        $this->expectException(InvalidArgumentException::class);

        new Hsla(348, 100, 50, 'apples');
    }
}
