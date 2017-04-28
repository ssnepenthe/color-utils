<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\HslaParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HslaParserTest extends TestCase
{
    /** @test */
    function it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new HslaParser;

        /*
        MATCHING CONDITIONS

        general:    "hsla" prefix is required, channels are surrounded by parens,
                    channels are delimited by ",", should match with or without
                    spacing between channels, case-insensitive.
        hue:        1 - 3 digits, optional "." followed by up to 5 digits.
        saturation: 1 - 3 digits, must end in "%", optional "." followed by up to 5
                    digits.
        lightness:  1 - 3 digits, must end in "%", optional "." followed by up to 5
                    digits.
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
            // Starts at 0.
            'hsla(0, 100%, 100%, 1.0)',
            'hsla(5, 100%, 100%, 1.0)',
            'hsla(10, 100%, 100%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
            'hsla(360, 100%, 100%, 1.0)',
            // Works with any 3-digit number even if greater than max allowed.
            'hsla(999, 100%, 100%, 1.0)',
            // Fractional values up to 5 digits.
            'hsla(123.4, 100%, 100%, 1.0)',
            'hsla(123.45678, 100%, 100%, 1.0)',
        ];
        $matchingSaturation = [
            // Starts at 0.
            'hsla(100, 0%, 100%, 1.0)',
            'hsla(100, 5%, 100%, 1.0)',
            'hsla(100, 10%, 100%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
            // Works with any 3-digit number.
            'hsla(100, 999%, 100%, 1.0)',
            // Fractional values up to 5 digits.
            'hsla(100, 45.2%, 100%, 1.0)',
            'hsla(100, 45.23456%, 100%, 1.0)',
        ];
        $matchingLightness = [
            // Starts at 0.
            'hsla(100, 100%, 0%, 1.0)',
            'hsla(100, 100%, 5%, 1.0)',
            'hsla(100, 100%, 10%, 1.0)',
            'hsla(100, 100%, 100%, 1.0)',
            // Works with any 3-digit number.
            'hsla(100, 100%, 999%, 1.0)',
            // Fractional values up to 5 digits.
            'hsla(100, 100%, 64.7%, 1.0)',
            'hsla(100, 100%, 64.56789%, 1.0)',
        ];
        $matchingAlpha = [
            // Starts with 0 or 1.
            'hsla(100, 100%, 100%, 0)',
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
            // "." with no trailing digits.
            'hsla(123., 100%, 100%, 1.0)',
            // "." with too many trailing digits.
            'hsla(123.456789, 100%, 100%, 1.0)',
        ];
        $failingSaturation = [
            // No "%".
            'hsla(100, 1, 100%, 1.0)',
            // Non-numeric characters.
            'hsla(100, I00%, 100%, 1.0)',
            // Too many digits.
            'hsla(100, 1000%, 100%, 1.0)',
            // "." with no trailing digits.
            'hsla(100, 45.%, 100%, 1.0)',
            // "." with too many trailing digits.
            'hsla(100, 54.123456%, 100%, 1.0)',
        ];
        $failingLightness = [
            // No "%".
            'hsla(100, 100%, 100, 1.0)',
            // Non-numeric characters.
            'hsla(100, 100%, I00%, 1.0)',
            // Too many digits.
            'hsla(100, 100%, 1000%, 1.0)',
            // "." with no trailing digits.
            'hsla(100, 100%, 64.%, 1.0)',
            // "." with too many trailing digits.
            'hsla(100, 100%, 32.098765%, 1.0)',
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

    /** @test */
    function it_correctly_parses_hsla_strings()
    {
        $parser = new HslaParser;

        $this->assertEquals(
            ['hue' => 120, 'saturation' => 95, 'lightness' => 85, 'alpha' => 0.5],
            $parser->parse('hsla(120, 95%, 85%, 0.5)')
        );
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $parser = new HslaParser;
        $parser->parse('hsl(120, 95%, 85%)');
    }
}
