<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Saturate;

class SaturateTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_saturate_colors()
    {
        // assert_equal("#d9f2d9", evaluate("saturate(hsl(120, 30, 90), 20%)"))
        $c = Color::fromHsl(120, 30, 90);
        $t = new Saturate(20);
        $this->assertEquals('#d9f2d9', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("#9e3f3f", evaluate("saturate(#855, 20%)"))
        $c = Color::fromString('#855');
        $t = new Saturate(20);
        $this->assertEquals('#9e3f3f', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("rgba(158, 63, 63, 0.5)", evaluate("saturate(rgba(136, 85, 85, 0.5), 20%)"))
        $c = Color::fromRgb(136, 85, 85, 0.5);
        $t = new Saturate(20);
        $this->assertEquals('rgba(158, 63, 63, 0.5)', $t->transform($c));
    }

    public function test_saturating_shades_of_gray_doesnt_change_the_color()
    {
        $c = Color::fromString('#000');

        // assert_equal("black", evaluate("saturate(#000, 20%)"))
        $t = new Saturate(20);
        $this->assertEquals('black', $t->transform($c)->getName());

        $c = Color::fromString('#fff');

        // assert_equal("white", evaluate("saturate(#fff, 20%)"))
        $t = new Saturate(20);
        $this->assertEquals('white', $t->transform($c)->getName());
    }

    public function test_it_honors_ranges_when_saturating_colors()
    {
        $c = Color::fromString('#8a8');

        // assert_equal("#33ff33", evaluate("saturate(#8a8, 100%)"))
        $t = new Saturate(100);
        $this->assertEquals('#33ff33', $t->transform($c)->getRgb()->toHexString());
    }

    public function test_it_doesnt_change_the_color_when_given_a_zero_amount()
    {
        $c = Color::fromString('#8a8');

        // assert_equal("#88aa88", evaluate("saturate(#8a8, 0%)"))
        $t = new Saturate(0);
        $this->assertEquals('#88aa88', $t->transform($c)->getRgb()->toHexString());
    }
}
