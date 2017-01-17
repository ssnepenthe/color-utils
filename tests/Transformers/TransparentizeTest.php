<?php

use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Transparentize;

class TransparentizeTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_add_transparency_to_colors()
    {
        $t = new Transparentize(0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("transparentize(rgba(0, 0, 0, 0.5), 0.2)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0.3)',
            $t->transform(Color::fromRgb(0, 0, 0, 0.5))
        );

        // assert_equal("transparent", evaluate("fade_out(rgba(0, 0, 0, 0.2), 0.2)"))
        $this->assertEquals(
            'transparent',
            $t->transform(Color::fromRgb(0, 0, 0, 0.2))->getName()
        );

        $t = new Transparentize(0);

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0.2)',
            $t->transform(Color::fromRgb(0, 0, 0, 0.2))
        );

        $t = new Transparentize(0.1);

        // assert_equal("rgba(0, 0, 0, 0.1)", evaluate("transparentize(rgba(0, 0, 0, 0.2), 0.1)"))
        $this->assertEquals(
            'rgba(0, 0, 0, 0.1)',
            $t->transform(Color::fromRgb(0, 0, 0, 0.2))
        );

        $t = new Transparentize(1);

        // assert_equal("transparent", evaluate("transparentize(rgba(0, 0, 0, 0.2), 1)"))
        $this->assertEquals(
            'transparent',
            $t->transform(Color::fromRgb(0, 0, 0, 1))->getName()
        );
    }
}
