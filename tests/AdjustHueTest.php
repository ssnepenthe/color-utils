<?php

use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\adjust_hue;
use SSNepenthe\ColorUtils\Transformers\AdjustHue;

class AdjustHueTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_positively_adjust_hue_from_hsl()
    {
        // assert_equal("#deeded", evaluate("adjust-hue(hsl(120, 30, 90), 60deg)"))
        $c = Color::fromHsl(120, 30, 90);
        $t = new AdjustHue(60);
        $this->assertEquals('#deeded', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_positively_adjust_hue_from_hex()
    {
        // assert_equal("#886a11", evaluate("adjust-hue(#811, 45deg)"))
        $c = Color::fromString('#811');
        $t = new AdjustHue(45);
        $this->assertEquals('#886a11', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_can_positively_adjust_hue_from_rgb()
    {
        // assert_equal("rgba(136, 106, 17, 0.5)", evaluate("adjust-hue(rgba(136, 17, 17, 0.5), 45deg)"))
        $c = Color::fromRgb(136, 17, 17, 0.5);
        $t = new AdjustHue(45);
        $this->assertEquals('rgba(136, 106, 17, 0.5)', (string) $t->transform($c));
    }

    public function test_it_can_negatively_adjust_hue()
    {
        // assert_equal("#ededde", evaluate("adjust-hue(hsl(120, 30, 90), -60deg)"))
        $c = Color::fromHsl(120, 30, 90);
        $t = new AdjustHue(-60);
        $this->assertEquals('#ededde', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_cant_adjust_shades_of_gray()
    {
        // assert_equal("black", evaluate("adjust-hue(#000, 45deg)"))
        $c = Color::fromString('#000');
        $t = new AdjustHue(45);
        $this->assertEquals('black', $t->transform($c)->getRgb()->getName());

        // assert_equal("white", evaluate("adjust-hue(#fff, 45deg)"))
        $c = Color::fromString('#fff');
        $t = new AdjustHue(45);
        $this->assertEquals('white', $t->transform($c)->getRgb()->getName());
    }

    public function test_adjustments_of_0_or_360_dont_change_a_color()
    {
        $c = Color::fromString('#8a8');

        foreach ([new AdjustHue(360), new AdjustHue(0)] as $t) {
            $this->assertEquals(
                '#88aa88',
                $t->transform($c)->getRgb()->toHexString()
            );
        }
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('black'),
            Rgb::fromString('black'),
            Hsl::fromString('hsl(0, 0%, 0%)'),
        ];

        $transformer = new AdjustHue(180);

        foreach ($colors as $color) {
            $this->assertEquals(
                [180, 0, 0],
                $transformer->transform($color)->getHsl()->toArray()
            );
        }
    }

    public function test_functional_wrapper()
    {
        $color = adjust_hue(Color::fromString('black'), 180);
        $this->assertEquals([180, 0, 0], $color->getHsl()->toArray());
    }
}
