<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Transformers\AdjustHue;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Tests duplicated from SASS.
 *
 * @link https://github.com/sass/sass/blob/stable/test/sass/functions_test.rb
 */
class AdjustHueTest extends TestCase
{
    /** @test */
    function it_can_positively_adjust_hue()
    {
        // assert_equal("#deeded", evaluate("adjust-hue(hsl(120, 30, 90), 60deg)"))
        $t = new AdjustHue(60);
        $c = ColorFactory::fromHsl(120, 30, 90);
        $this->assertEquals('#deeded', $t->transform($c)->toHexString());

        $t = new AdjustHue(45);

        // assert_equal("#886a11", evaluate("adjust-hue(#811, 45deg)"))
        $c = ColorFactory::fromString('#811');
        $this->assertEquals('#886a11', $t->transform($c)->toHexString());

        // assert_equal("rgba(136, 106, 17, 0.5)", evaluate("adjust-hue(rgba(136, 17, 17, 0.5), 45deg)"))
        $c = ColorFactory::fromRgba(136, 17, 17, 0.5);
        $this->assertEquals('rgba(136, 106, 17, 0.5)', (string) $t->transform($c));
    }

    /** @test */
    function it_can_negatively_adjust_hue()
    {
        // assert_equal("#ededde", evaluate("adjust-hue(hsl(120, 30, 90), -60deg)"))
        $c = ColorFactory::fromHsl(120, 30, 90);
        $t = new AdjustHue(-60);
        $this->assertEquals('#ededde', $t->transform($c)->toHexString());
    }

    /** @test */
    function it_cant_adjust_shades_of_gray()
    {
        // assert_equal("black", evaluate("adjust-hue(#000, 45deg)"))
        $c = ColorFactory::fromString('#000');
        $t = new AdjustHue(45);
        $this->assertEquals('black', $t->transform($c)->getName());

        // assert_equal("white", evaluate("adjust-hue(#fff, 45deg)"))
        $c = ColorFactory::fromString('#fff');
        $t = new AdjustHue(45);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    /** @test */
    function adjustments_of_360_creates_the_same_color()
    {
        $t = new AdjustHue(360);

        $this->assertEquals(
            '#88aa88',
            $t->transform(ColorFactory::fromString('#8a8'))->toHexString()
        );
    }

    /** @test */
    function it_throws_when_given_invalid_adjustments()
    {
        // SASS allows this, I don't like it.
        $this->expectException(InvalidArgumentException::class);

        $t = new AdjustHue(0);
    }
}
