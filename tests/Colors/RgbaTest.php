<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use SSNepenthe\ColorUtils\Colors\Rgba;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class RgbaTest extends TestCase
{
    /** @test */
    function it_is_instantiable(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);

        $this->assertInstanceOf(Rgba::class, $rgba);
        $this->assertInstanceOf(ColorInterface::class, $rgba);
    }

    /** @test */
    function it_forces_a_0_to_1_range_for_alpha(): void
    {
        $rgba = new Rgba(0, 0, 0, -0.1);
        $rgba2 = new Rgba(0, 0, 0, 1.1);

        $this->assertEquals('rgba(0, 0, 0, 0)', $rgba);
        $this->assertEquals('rgb(0, 0, 0)', $rgba2);
    }

    /** @test */
    function it_can_be_cast_to_a_string(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);

        $this->assertEquals('rgba(255, 0, 51, 0.7)', (string) $rgba);
        $this->assertEquals('rgba(255, 0, 51, 0.7)', $rgba->toString());
    }

    /** @test */
    function it_correctly_formats_alpha_channel_in_string_conversion(): void
    {
        /*

        FORMATTING RULES:

        Alpha is dropped if == 1.0.
        All trailing "0" characters are trimmed.
        "." character is removed if there is nothing to the right of it.

         */
        $map = [
            'rgb(255, 0, 51)'        => new Rgba(255, 0, 51, 1.0),
            'rgba(255, 0, 51, 0)'    => new Rgba(255, 0, 51, 0.0),
            'rgba(255, 0, 51, 0.12)' => new Rgba(255, 0, 51, 0.123456),
            'rgba(255, 0, 51, 0.1)'  => new Rgba(255, 0, 51, 0.101),
        ];

        foreach ($map as $string => $rgba) {
            $this->assertEquals($string, $rgba->toString());
        }
    }

    /** @test */
    function channel_getters_give_correct_value(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);

        $this->assertEquals(0.7, $rgba->getAlpha());
        $this->assertTrue($rgba->hasAlpha());
    }

    /** @test */
    function it_correctly_produces_rgb_array(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);
        $rgba2 = new Rgba(255, 0, 51, 1);

        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 51, 'alpha' => 0.7],
            $rgba->toArray()
        );
        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 51],
            $rgba2->toArray()
        );
    }

    /** @test */
    function it_correctly_produces_color_instance(): void
    {
        $this->assertInstanceOf(
            Color::class,
            (new Rgba(255, 0, 51, 0.7))->toColor()
        );
    }

    /** @test */
    function it_correctly_produces_hex_array(): void
    {
        $one = new Rgba(255, 0, 51, 1.0);
        $two = new Rgba(255, 0, 51, 0.7);

        $this->assertEquals(
            ['red' => 'ff', 'green' => '00', 'blue' => '33'],
            $one->toHexArray()
        );
        $this->assertEquals(
            ['red' => 'ff', 'green' => '00', 'blue' => '33', 'alpha' => 'b3'],
            $two->toHexArray()
        );
    }

    /** @test */
    function it_correctly_produces_hex_string(): void
    {
        $one = new Rgba(255, 0, 51, 1.0);
        $two = new Rgba(255, 0, 51, 0.7);

        $this->assertEquals('#ff0033', $one->toHexString());
        $this->assertEquals('#ff0033b3', $two->toHexString());
    }

    /**
     * @link https://github.com/ssnepenthe/color-utils/issues/6
     *
     * @test
     */
    function it_accounts_for_alpha_value_when_determining_color_name(): void
    {
        $purple = new Rgba(128, 0, 128, 1.0);
        $notPurple = new Rgba(128, 0, 128, 0.7);

        $this->assertEquals('purple', $purple->getName());
        $this->assertEquals('', $notPurple->getName());
    }

    /** @test */
    function it_can_create_a_modified_version_of_itself(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);
        $rgba2 = $rgba->with(['blue' => 0]);
        $rgba3 = $rgba->with(['red' => 0, 'blue' => 0]);

        $this->assertEquals('rgba(255, 0, 0, 0.7)', $rgba2);
        $this->assertEquals('rgba(0, 0, 0, 0.7)', $rgba3);
    }

    /** @test */
    function it_can_create_a_version_of_itself_without_transparency(): void
    {
        $rgba = new Rgba(255, 0, 51, 0.7);
        $rgb = $rgba->with(['red' => 0, 'blue' => 0, 'alpha' => 1]);

        // Should be Rgb instance.
        $this->assertNotInstanceOf(Rgba::class, $rgb);
        $this->assertEquals('rgb(0, 0, 0)', $rgb);
    }

    /** @test */
    function it_cant_be_instantiated_with_non_numeric_alpha(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Rgba(123, 123, 123, 'apples');
    }
}
