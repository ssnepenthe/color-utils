<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Rgb;
use SSNepenthe\ColorUtils\Colors\Hsla;
use SSNepenthe\ColorUtils\Colors\Rgba;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Exceptions\BadMethodCallException;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class ColorTest extends TestCase
{
    /** @test */
    function it_is_instantiable()
    {
        $color = new Color(new Rgb(255, 0, 51));

        $this->assertInstanceOf(Color::class, $color);
    }

    /** @test */
    function it_correctly_proxies_all_rgb_methods()
    {
        $color = new Color(new Rgb(255, 0, 51));

        $this->assertEquals(140.49551, $color->calculatePerceivedBrightness());

        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(51, $color->getBlue());
        $this->assertEquals(1.0, $color->getAlpha());
        $this->assertFalse($color->hasAlpha());

        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 51],
            $color->toArray()
        );
        $this->assertEquals('rgb(255, 0, 51)', $color->toString());
        $this->assertEquals('rgb(255, 0, 51)', $color->__toString());
        $this->assertEquals('rgb(255, 0, 51)', (string) $color);

        $this->assertEquals('ff', $color->getRedByte());
        $this->assertEquals('00', $color->getGreenByte());
        $this->assertEquals('33', $color->getBlueByte());
        $this->assertEquals('ff', $color->getAlphaByte());

        $this->assertEquals(
            ['red' => 'ff', 'green' => '00', 'blue' => '33'],
            $color->toHexArray()
        );
        $this->assertEquals('#ff0033', $color->toHexString());

        $this->assertEquals('', $color->getName());

        $this->assertTrue($color->looksBright());
        $this->assertFalse($color->looksBright(150));

        $this->assertSame($color, $color->toColor());
    }

    /** @test */
    function it_correctly_proxies_all_hsl_methods()
    {
        $color = new Color(new Hsl(348, 100, 50));

        $this->assertEquals(348.0, $color->getHue());
        $this->assertEquals(100.0, $color->getSaturation());
        $this->assertEquals(50.0, $color->getLightness());
        $this->assertEquals(1.0, $color->getAlpha());
        $this->assertFalse($color->hasAlpha());

        $this->assertEquals('hsl(348, 100%, 50%)', $color->toString());
        $this->assertEquals('hsl(348, 100%, 50%)', $color->__toString());
        $this->assertEquals('hsl(348, 100%, 50%)', (string) $color);

        $this->assertTrue($color->isLight());
        $this->assertFalse($color->isLight(55));

        $this->assertEquals(
            ['hue' => 348.0, 'saturation' => 100.0, 'lightness' => 50.0],
            $color->toArray()
        );

        $this->assertSame($color, $color->toColor());
    }

    /** @test */
    function it_can_override_base_color_on_instantiation()
    {
        $rgb = new Color(new Rgb(255, 0, 51));
        $rgba = new Color(new Rgba(255, 0, 51, 0.7));
        $hsl = new Color(new Rgb(255, 0, 51), Hsl::class);
        $hsla = new Color(new Rgba(255, 0, 51, 0.7), Hsla::class); // Could also use Hsl.

        $this->assertEquals('rgb(255, 0, 51)', $rgb->toString());
        $this->assertEquals('rgba(255, 0, 51, 0.7)', $rgba->toString());
        $this->assertEquals('hsl(348, 100%, 50%)', $hsl->toString());
        $this->assertEquals('hsla(348, 100%, 50%, 0.7)', $hsla->toString());
    }

    /** @test */
    function it_can_calculate_brightness_difference_with_a_color()
    {
        $color1 = new Color(new Rgb(255, 0, 51));
        $color2 = new Color(new Rgb(51, 0, 255));

        $this->assertEquals(
            37.74,
            $color1->calculateBrightnessDifferenceWith($color2)
        );
        $this->assertEquals(
            37.74,
            $color2->calculateBrightnessDifferenceWith($color1)
        );
    }

    /** @test */
    function it_can_calculate_color_difference_with_a_color()
    {
        $color1 = new Color(new Rgb(255, 0, 51));
        $color2 = new Color(new Rgb(51, 0, 255));

        $this->assertEquals(408, $color1->calculateColorDifferenceWith($color2));
        $this->assertEquals(408, $color2->calculateColorDifferenceWith($color1));
    }

    /** @test */
    function it_can_calculate_contrast_ratio_with_a_color()
    {
        $color1 = new Color(new Rgb(255, 0, 51));
        $color2 = new Color(new Rgb(51, 0, 255));

        $this->assertEquals(2.05037, $color1->calculateContrastRatioWith($color2));
        $this->assertEquals(2.05037, $color2->calculateContrastRatioWith($color1));
    }

    /** @test */
    function it_can_retrieve_individual_representations()
    {
        $color = new Color(new Rgb(255, 0, 51));

        $this->assertInstanceOf(Rgb::class, $color->getRgb());
        $this->assertInstanceOf(Hsl::class, $color->getHsl());
    }

    /** @test */
    function it_can_create_a_modified_versions_of_itself()
    {
        $white = (new Color(new Rgb(255, 255, 0)))->with(['blue' => 255]);
        $blue = (new Color(new Hsl(348, 100, 50)))->with(['hue' => 240]);
        $transparentRed = (new Color(new Rgb(255, 0, 0)))->with(['alpha' => 0]);
        $transparentPurple = (new Color(new Rgb(255, 0, 0)))->with([
            'alpha' => 0.5,
            'blue' => 255
        ]);
        $transparent = (new Color(new Hsl(348, 100, 50)))->with([
            'alpha' => 0.5,
            'hue' => 270
        ]);

        $this->assertEquals('rgb(255, 255, 255)', $white);
        $this->assertEquals('hsl(240, 100%, 50%)', $blue);
        $this->assertEquals('rgba(255, 0, 0, 0)', $transparentRed);
        $this->assertEquals('rgba(255, 0, 255, 0.5)', $transparentPurple);
        $this->assertEquals('hsla(270, 100%, 50%, 0.5)', $transparent);
    }

    /** @test */
    function it_creates_a_new_color_with_the_same_type_as_original()
    {
        $hsl = new Color(new Hsl(60, 100, 50));
        $rgb = new Color(new Rgb(255, 255, 0));

        $this->assertEquals('hsl(120, 100%, 50%)', $hsl->with(['red' => 0]));
        $this->assertEquals('rgb(0, 255, 0)', $rgb->with(['red' => 0]));
    }

    /** @test */
    function it_throws_exception_for_non_existent_methods()
    {
        $this->expectException(BadMethodCallException::class);

        (new Color(new Rgb(255, 0, 51)))->notARealMethod();
    }

    /** @test */
    function it_cant_modify_hsl_and_rgb_in_same_operation()
    {
        $this->expectException(InvalidArgumentException::class);

        (new Color(new Rgb(255, 255, 0)))->with(['blue' => 255, 'hue' => 360]);
    }

    /** @test */
    function it_cant_create_a_new_color_without_any_changes()
    {
        $this->expectException(InvalidArgumentException::class);

        (new Color(new Rgb(255, 255, 0)))->with(['not' => 'real']);
    }
}
