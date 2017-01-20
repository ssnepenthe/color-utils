<?php

use SSNepenthe\ColorUtils\Parsers\HslParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HslParserTest extends PHPUnit_Framework_TestCase
{
    public function test_it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new HslParser;

        /*
        MATCHING CONDITIONS

        general:    "hsl" prefix is required, channels are surrounded by parens,
                    channels are delimited by ",", should match with or without
                    spacing between channels, case-insensitive.
        hue:        1 - 3 digits
        saturation: 1 - 3 digits, must end in "%"
        lightness:  1 - 3 digits, must end in "%"
         */

        $matchingGeneral = [
            'hsl(100, 100%, 100%)',
            'hsl(100,100%,100%)',
            'HSL(100, 100%, 100%)',
        ];
        $matchingHue = [
            'hsl(1, 100%, 100%)',
            'hsl(10, 100%, 100%)',
            'hsl(100, 100%, 100%)',
            'hsl(360, 100%, 100%)',
        ];
        $matchingSaturation = [
            'hsl(100, 1%, 100%)',
            'hsl(100, 10%, 100%)',
            'hsl(100, 100%, 100%)',
        ];
        $matchingLightness = [
            'hsl(100, 100%, 1%)',
            'hsl(100, 100%, 10%)',
            'hsl(100, 100%, 100%)',
        ];

        $failingGeneral = [
            // Wrong prefix.
            'hsla(100, 100%, 100%)',
            // Wrong brackets.
            'hsl{100, 100%, 100%}',
            // Wrong delimiter.
            'hsl(100. 100%. 100%)',
        ];
        $failingHue = [
            // Non-numeric characters.
            'hsl(zero, 100%, 100%)',
            // Too many digits.
            'hsl(0000, 100%, 100%)',
        ];
        $failingSaturation = [
            // No "%".
            'hsl(100, 1, 100%)',
            // Non-numeric characters.
            'hsl(100, I00%, 100%)',
            // Too many digits.
            'hsl(100, 1000%, 100%)',
        ];
        $failingLightness = [
            // No "%".
            'hsl(100, 100%, 100)',
            // Non-numeric characters.
            'hsl(100, 100%, I00%)',
            // Too many digits.
            'hsl(100, 100%, 1000%)',
        ];

        $hslMatches = [
            $matchingGeneral,
            $matchingHue,
            $matchingSaturation,
            $matchingLightness,
        ];

        $hslFailures = [
            $failingGeneral,
            $failingHue,
            $failingSaturation,
            $failingLightness,
        ];

        foreach ($hslMatches as $channelMatches) {
            foreach ($channelMatches as $match) {
                $this->assertTrue($parser->supports($match));
            }
        }

        foreach ($hslFailures as $channelFailures) {
            foreach ($channelFailures as $failure) {
                $this->assertFalse($parser->supports($failure));
            }
        }
    }

    public function test_it_correctly_parses_hsla_strings()
    {
        $parser = new HslParser;

        $this->assertEquals(
            ['hue' => 120, 'saturation' => 95, 'lightness' => 85],
            $parser->parse('hsl(120, 95%, 85%)')
        );

        try {
            $parser->parse('hsla(120, 95%, 85%, 0.5)');

            $this->fail(
                'HslParser::parse() throws exception when attempting to parse unsupported string'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
