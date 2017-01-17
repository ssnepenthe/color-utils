<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Darken;

class DarkenTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_darken_colors()
    {
        // assert_equal("#ff6a00", evaluate("darken(hsl(25, 100, 80), 30%)"))
        $c = Color::fromHsl(25, 100, 80);
        $t = new Darken(30);
        $this->assertEquals('#ff6a00', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("#220000", evaluate("darken(#800, 20%)"))
        $c = Color::fromString('#800');
        $t = new Darken(20);
        $this->assertEquals('#220000', $t->transform($c)->getRgb()->toHexString());

        // assert_equal("rgba(34, 0, 0, 0.5)", evaluate("darken(rgba(136, 0, 0, 0.5), 20%)"))
        $c = Color::fromRgb(136, 0, 0, 0.5);
        $t = new Darken(20);
        $this->assertEquals('rgba(34, 0, 0, 0.5)', $t->transform($c));
    }

    public function test_it_can_only_go_as_dark_as_black()
    {
        // assert_equal("black", evaluate("darken(#000, 20%)"))
        $c = Color::fromString('#000');
        $t = new Darken(20);
        $this->assertEquals('black', $t->transform($c)->getName());

        // assert_equal("black", evaluate("darken(#800, 100%)"))
        $c = Color::fromString('#800');
        $t = new Darken(100);
        $this->assertEquals('black', $t->transform($c)->getName());
    }

    public function test_darkening_by_0_does_not_change_color()
    {
        $c = Color::fromString('#800');

        // assert_equal("#880000", evaluate("darken(#800, 0%)"))
        $t = new Darken(0);
        $this->assertEquals('#880000', $t->transform($c)->getRgb()->toHexString());
    }
}
