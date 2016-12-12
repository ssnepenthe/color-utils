<?php


use SSNepenthe\ColorUtils\Color;
use SSNepenthe\ColorUtils\Transformers\Opacify;

class OpacifyTest extends TransformerTestCase
{
    public function test_it_can_add_opacity_to_colors()
    {
        $color = Color::fromRgb(0, 0, 0, 0.2);

        $tests = [
            // assert_equal("rgba(0, 0, 0, 0.3)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0.1)"))
            ['transformer' => new Opacify(0.1), 'result' => [0, 0, 0, 0.3]],
            // assert_equal("black", evaluate("fade_in(rgba(0, 0, 0, 0.2), 0.8)"))
            ['transformer' => new Opacify(0.8), 'result' => [0, 0, 0, 1.0]],
            // assert_equal("black", evaluate("opacify(rgba(0, 0, 0, 0.2), 1)"))
            ['transformer' => new Opacify(1.0), 'result' => [0, 0, 0, 1.0]],
            // assert_equal("rgba(0, 0, 0, 0.2)", evaluate("opacify(rgba(0, 0, 0, 0.2), 0%)"))
            ['transformer' => new Opacify(0.0), 'result' => [0, 0, 0, 0.2]],
        ];

        $this->runTransformerTests($color, $tests);

        $transformer = new Opacify(0.25);

        // assert_equal("rgba(0, 0, 0, 0.75)", evaluate("opacify(rgba(0, 0, 0, 0.5), 0.25)"))
        $this->assertEquals(
            [0, 0, 0, 0.75],
            $transformer->transform(Color::fromRgb(0, 0, 0, 0.5))->toArray()
        );
    }
}