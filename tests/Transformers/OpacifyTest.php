<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Opacify;

class OpacifyTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_add_opacity_to_colors()
    {
        $c = Color::fromRgb(0, 0, 0, 0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0.1)"))
        $t = new Opacify(0.1);
        $this->assertEquals('rgba(0, 0, 0, 0.3)', $t->transform($c));

        // assert_equal("black", evaluate("fade_in(rgba(0, 0, 0, 0.2), 0.8)"))
        $t = new Opacify(0.8);
        $this->assertEquals('black', $t->transform($c)->getName());

        // assert_equal("black", evaluate("opacify(rgba(0, 0, 0, 0.2), 1)"))
        $t = new Opacify(1.0);
        $this->assertEquals('black', $t->transform($c)->getName());

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0%)"))
        $t = new Opacify(0.0);
        $this->assertEquals('rgba(0, 0, 0, 0.2)', $t->transform($c));

        $c = Color::fromRgb(0, 0, 0, 0.5);
        $t = new Opacify(0.25);

        // assert_equal("rgba(0, 0, 0, 0.75)", evaluate("opacify(rgba(0, 0, 0, 0.5), 0.25)"))
        $this->assertEquals('rgba(0, 0, 0, 0.75)', $t->transform($c));
    }
}
