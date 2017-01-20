<?php

use SSNepenthe\ColorUtils\Parsers\HslaParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HslaParserTest extends PHPUnit_Framework_TestCase
{
    public function test_it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new HslaParser;

        /*
        MATCHING CONDITIONS

        general:    "hsla" prefix is required, channels are surrounded by parens,
                    channels are delimited by ",", should match with or without
                    spacing between channels, case-insensitive.
        hue:        1 - 3 digits
        saturation: 1 - 3 digits, must end in "%"
        lightness:  1 - 3 digits, must end in "%"
        alpha:      leading 1 or 0 is required, "." and trailings digits are
                    optional, if "." is omitted, trailing digits are not allowed, 5
                    digits max after ".".
         */

        $matchingGeneral = [
            'hsla(100, 100%, 100%, 1.0)',
            'hsla(100,100%,100%,1.0)',
            'HSLA(100, 100%, 100%, 1.0)',
        ];
        $matchingHue = [
            'hsla(1, 100%, 100%, 1.0)',
            'hsla(10, 100%, 100%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
            'hsla(360, 100%, 100%, 1.0)',
        ];
        $matchingSaturation = [
            'hsla(100, 1%, 100%, 1.0)',
            'hsla(100, 10%, 100%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
        ];
        $matchingLightness = [
            'hsla(100, 100%, 1%, 1.0)',
            'hsla(100, 100%, 10%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
        ];
        $matchingAlpha = [
            'hsla(100, 100%, 100%, 1)',
            'hsla(100, 100%, 100%, 0.1)',
            'hsla(100, 100%, 100%, 0.11)',
            'hsla(100, 100%, 100%, 0.111)',
            'hsla(100, 100%, 100%, 0.1111)',
            'hsla(100, 100%, 100%, 0.11111)',
        ];

        $failingGeneral = [
            // Wrong prefix.
            'hsl(100, 100%, 100%, 1.0)',
            // Wrong brackets.
            'hsla{100, 100%, 100%, 1.0}',
            // Wrong delimiter.
            'hsla(100. 100%. 100%. 1.0)',
        ];
        $failingHue = [
            // Non-numeric characters.
            'hsla(zero, 100%, 100%, 1.0)',
            // Too many digits.
            'hsla(0000, 100%, 100%, 1.0)',
        ];
        $failingSaturation = [
            // No "%".
            'hsla(100, 1, 100%, 1.0)',
            // Non-numeric characters.
            'hsla(100, I00%, 100%, 1.0)',
            // Too many digits.
            'hsla(100, 1000%, 100%, 1.0)',
        ];
        $failingLightness = [
            // No "%".
            'hsla(100, 100%, 100, 1.0)',
            // Non-numeric characters.
            'hsla(100, 100%, I00%, 1.0)',
            // Too many digits.
            'hsla(100, 100%, 1000%, 1.0)',
        ];
        $failingAlpha = [
            // Leading 1 or 0 required.
            'hsla(100, 100%, 100%, .1)',
            // Trailing digits required if "." is present.
            'hsla(100, 100%, 100%, 1.)',
            // Too many digits after ".".
            'hsla(100, 100%, 100%, 0.111111)',
        ];

        $hslaMatches = [
            $matchingGeneral,
            $matchingHue,
            $matchingSaturation,
            $matchingLightness,
            $matchingAlpha
        ];

        $hslaFailures = [
            $failingGeneral,
            $failingHue,
            $failingSaturation,
            $failingLightness,
            $failingAlpha,
        ];

        foreach ($hslaMatches as $channelMatches) {
            foreach ($channelMatches as $match) {
                $this->assertTrue($parser->supports($match));
            }
        }

        foreach ($hslaFailures as $channelFailures) {
            foreach ($channelFailures as $failure) {
                $this->assertFalse($parser->supports($failure));
            }
        }
    }

    public function test_it_correctly_parses_hsla_strings()
    {
        $parser = new HslaParser;

        $this->assertEquals(
            ['hue' => 120, 'saturation' => 95, 'lightness' => 85, 'alpha' => 0.5],
            $parser->parse('hsla(120, 95%, 85%, 0.5)')
        );

        try {
            $parser->parse('hsl(120, 95%, 85%)');

            $this->fail(
                'HslaParser::parse() throws exception when attempting to parse unsupported string'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
