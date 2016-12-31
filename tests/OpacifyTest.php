<?php


use SSNepenthe\ColorUtils\Hsl;
use SSNepenthe\ColorUtils\Rgb;
use SSNepenthe\ColorUtils\Color;
use function SSNepenthe\ColorUtils\opacify;
use SSNepenthe\ColorUtils\Transformers\Opacify;

class OpacifyTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_add_opacity_to_colors()
    {
        $c = Color::fromRgb(0, 0, 0, 0.2);

        // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0.1)"))
        $t = new Opacify(0.1);
        $this->assertEquals('rgba(0, 0, 0, 0.3)', $t->transform($c));

        // @todo Can't use the name here because we are given the alpha byte...
        // assert_equal("black", evaluate("fade_in(rgba(0, 0, 0, 0.2), 0.8)"))
        $t = new Opacify(0.8);
        $this->assertEquals('rgba(0, 0, 0, 1)', $t->transform($c));

        // @todo See note above.
        // assert_equal("black", evaluate("opacify(rgba(0, 0, 0, 0.2), 1)"))
        $t = new Opacify(1.0);
        $this->assertEquals('rgba(0, 0, 0, 1)', $t->transform($c));

        // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0%)"))
        $t = new Opacify(0.0);
        $this->assertEquals('rgba(0, 0, 0, 0.2)', $t->transform($c));

        $c = Color::fromRgb(0, 0, 0, 0.5);
        $t = new Opacify(0.25);

        // assert_equal("rgba(0, 0, 0, 0.75)", evaluate("opacify(rgba(0, 0, 0, 0.5), 0.25)"))
        $this->assertEquals('rgba(0, 0, 0, 0.75)', $t->transform($c));
    }

    public function test_it_can_transform_any_instance_of_color_interface()
    {
        $colors = [
            Color::fromString('transparent'),
            Rgb::fromString('transparent'),
            Hsl::fromString('hsl(0, 0%, 0%, 0.0)'),
        ];

        $transformer = new Opacify(0.5);

        foreach ($colors as $color) {
            $this->assertEquals(0.5, $transformer->transform($color)->getAlpha());
        }
    }

    public function test_functional_wrapper()
    {
        $color = opacify(Color::fromString('transparent'), 0.5);
        $this->assertEquals(0.5, $color->getAlpha());
    }
}
