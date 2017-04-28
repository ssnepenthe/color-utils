<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class ColorFactoryTest extends TestCase
{
    /** @test */
    function it_gets_the_values_right_no_matter_how_it_is_created()
    {
        $colors = [
            ColorFactory::fromString('hsl(348, 100%, 50%)'),
            ColorFactory::fromString('rgb(255, 0, 51)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals('rgb(255, 0, 51)', $color->getRgb());
        }

        $colors = [
            ColorFactory::fromString('hsla(348, 100%, 50%, 0.7)'),
            ColorFactory::fromString('rgba(255, 0, 51, 0.7)'),
        ];

        foreach ($colors as $color) {
            $this->assertEquals('rgba(255, 0, 51, 0.7)', $color->getRgb());
        }
    }

    /** @test */
    function it_can_create_colors_from_array()
    {
        $colors = [
            ColorFactory::fromArray(['red' => 255, 'green' => 0, 'blue' => 51]),
            ColorFactory::fromArray(
                ['red' => 255, 'green' => 0, 'blue' => 51, 'alpha' => 1]
            ),
            ColorFactory::fromArray(
                ['hue' => 348, 'saturation' => 100, 'lightness' => 50]
            ),
            ColorFactory::fromArray(
                ['hue' => 348, 'saturation' => 100, 'lightness' => 50, 'alpha' => 1]
            ),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
            $this->assertEquals('rgb(255, 0, 51)', $color->getRgb());
        }
    }

    /** @test */
    function it_can_create_color_from_hsl_values()
    {
        $colors = [
            ColorFactory::fromHsl(348, 100, 50),
            ColorFactory::fromHsla(348, 100, 50, 1),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
            $this->assertEquals('rgb(255, 0, 51)', $color->getRgb());
        }
    }

    /** @test */
    function it_can_create_color_from_rgb_values()
    {
        $colors = [
            ColorFactory::fromRgb(255, 0, 51),
            ColorFactory::fromRgba(255, 0, 51, 1),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
            $this->assertEquals('rgb(255, 0, 51)', $color->getRgb());
        }
    }

    /** @test */
    function it_can_create_colors_from_strings()
    {
        $colors = [
            ColorFactory::fromString('#ff0000'),
            ColorFactory::fromString('#ff0000ff'),
            ColorFactory::fromString('red'),
            ColorFactory::fromString('rgba(255, 0, 0, 1)'),
            ColorFactory::fromString('rgb(255, 0, 0)'),
            ColorFactory::fromString('hsla(0, 100%, 50%, 1)'),
            ColorFactory::fromString('hsl(0, 100%, 50%)'),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
            $this->assertEquals('rgb(255, 0, 0)', $color->getRgb());
        }
    }

    /** @test */
    function it_can_create_colors_from_unknown_args()
    {
        $colors = [
            ColorFactory::fromUnknown('#fff'),
            ColorFactory::fromUnknown(255, 255, 255),
            ColorFactory::fromUnknown(255, 255, 255, 1.0),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
        }
    }

    /** @test */
    function it_can_create_colors_from_single_unknown_arg()
    {
        $c1 = new Color(new Rgb(255, 255, 255));
        $c2 = ColorFactory::fromUnknownOneArg($c1);

        $this->assertSame($c1, $c2);

        $colors = [
            ColorFactory::fromUnknownOneArg(new Rgb(255, 255, 255)),
            ColorFactory::fromUnknownOneArg(
                ['red' => 255, 'green' => 255, 'blue' => 255]
            ),
            ColorFactory::fromUnknownOneArg('#ffffff'),
        ];

        foreach ($colors as $color) {
            $this->assertInstanceOf(Color::class, $color);
        }
    }

    /** @test */
    function it_can_create_colors_from_three_unknown_args()
    {
        $this->assertEquals(
            'rgb(255, 255, 255)',
            ColorFactory::fromUnknownThreeArgs(255, 255, 255)
        );

        $this->assertEquals(
            'hsl(320, 100%, 100%)',
            ColorFactory::fromUnknownThreeArgs(320, 100, 100)
        );

        // When args can be either Rgb or Hsl, favor Rgb.
        $this->assertEquals(
            'rgb(100, 75, 55)',
            ColorFactory::fromUnknownThreeArgs(100, 75, 55)
        );
    }

    /** @test */
    function it_can_create_colors_from_four_unknown_args()
    {
        $this->assertEquals(
            'rgba(255, 255, 255, 0.7)',
            ColorFactory::fromUnknownFourArgs(255, 255, 255, 0.7)
        );

        $this->assertEquals(
            'hsla(320, 100%, 100%, 0.7)',
            ColorFactory::fromUnknownFourArgs(320, 100, 100, 0.7)
        );

        // When args can be either Rgb or Hsl, favor Rgb.
        $this->assertEquals(
            'rgba(100, 75, 55, 0.7)',
            ColorFactory::fromUnknownFourArgs(100, 75, 55, 0.7)
        );
    }

    /** @test */
    function it_cant_create_a_color_without_a_complete_color_array()
    {
        $this->expectException(InvalidArgumentException::class);

        ColorFactory::fromArray(['one' => 25, 'green' => 35, 'blue' => 45]);
    }

    /** @test */
    function it_cant_create_an_unknown_color_with_bad_args()
    {
        $this->expectException(InvalidArgumentException::class);

        ColorFactory::fromUnknown(25, 35);
    }

    /** @test */
    function it_cant_create_a_color_from_four_args_if_out_of_bounds()
    {
        $this->expectException(InvalidArgumentException::class);

        ColorFactory::fromUnknownFourArgs(400, 100, 100, 0.9);
    }

    /** @test */
    function it_cant_create_a_color_from_one_arg_if_wrong_type()
    {
        $this->expectException(InvalidArgumentException::class);

        ColorFactory::fromUnknownOneArg(100);
    }

    /** @test */
    function it_cant_create_a_color_from_three_args_if_out_of_bounds()
    {
        $this->expectException(InvalidArgumentException::class);

        ColorFactory::fromUnknownThreeArgs(400, 100, 100);
    }
}
