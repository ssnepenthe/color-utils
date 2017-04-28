<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\RgbaParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class RgbaParserTest extends TestCase
{
    /** @test */
    function it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new RgbaParser;

        /*
        MATCHING CONDITIONS

        general: "rgba" prefix is required, not case sensitive, channels are
                 surrounded by parens, channels are delimited by ",", should match
                 with or without spacing between channels, red green and blue must be
                 all percent or none, fractions/decimals are not allowed for rgb.
        red:     1 - 3 digits, can optionally be percent
        green:   1 - 3 digits, can be percent, but only if red is percent
        blue:    1 - 3 digits, can be percent, but only if red and green are percent
        alpha:   leading 1 or 0 is required, "." and trailings digits are
                 optional, if "." is omitted, trailing digits are not allowed, 5
                 digits max after ".".
         */

        $matchingGeneral = [
            'rgba(120, 120, 120, 1.0)',
            'RGBA(120, 120, 120, 1.0)',
            'rgba(120,120,120,1.0)',
            'rgba(100%, 100%, 100%, 1.0)',
        ];
        $matchingRed = [
            'rgba(1, 0, 0, 1.0)',
            'rgba(11, 0, 0, 1.0)',
            'rgba(111, 0, 0, 1.0)',
        ];
        $matchingGreen = [
            'rgba(0, 1, 0, 1.0)',
            'rgba(0, 11, 0, 1.0)',
            'rgba(0, 111, 0, 1.0)',
        ];
        $matchingBlue = [
            'rgba(0, 0, 1, 1.0)',
            'rgba(0, 0, 11, 1.0)',
            'rgba(0, 0, 111, 1.0)',
        ];
        $matchingAlpha = [
            'rgba(0, 0, 0, 1)',
            'rgba(0, 0, 0, 0.1)',
            'rgba(0, 0, 0, 0.11)',
            'rgba(0, 0, 0, 0.111)',
            'rgba(0, 0, 0, 0.1111)',
            'rgba(0, 0, 0, 0.11111)',
        ];

        $failingGeneral = [
            // Wrong prefix.
            'rgb(0, 0, 0, 1.0)',
            // Wrong brackets.
            'rgba{0, 0, 0, 1.0}',
            // Wrong delimiter.
            'rgba(0. 0. 0. 1.0)',
            // Mixed int/percent in rgb.
            'rgba(0, 100%, 0, 1.0)',
            // Fractions not allowed for rgb.
            'rgba(0, 1.5, 0, 1.0)',
        ];
        $failingRed = [
            // Non-numeric characters.
            'rgba(I00, 0, 0, 1.0)',
            // Too many digits.
            'rgba(0000, 0, 0, 1.0)',
        ];
        $failingGreen = [
            // Non-numeric characters.
            'rgba(0, I00, 0, 1.0)',
            // Too many digits.
            'rgba(0, 0000, 0, 1.0)',
        ];
        $failingBlue = [
            // Non-numeric characters.
            'rgba(0, 0, I00, 1.0)',
            // Too many digits.
            'rgba(0, 0, 0000, 1.0)',
        ];
        $failingAlpha = [
            // Leading 1 or 0 required.
            'rgba(0, 0, 0, .1)',
            // Trailing digits required if "." is present.
            'rgba(0, 0, 0, 1.)',
            // Too many digits after ".".
            'rgba(0, 0, 0, 0.111111)',
        ];

        $rgbaMatches = [
            $matchingGeneral,
            $matchingRed,
            $matchingGreen,
            $matchingBlue,
            $matchingAlpha
        ];

        $rgbaFailures = [
            $failingGeneral,
            $failingRed,
            $failingGreen,
            $failingBlue,
            $failingAlpha,
        ];

        foreach ($rgbaMatches as $channelMatches) {
            foreach ($channelMatches as $match) {
                $this->assertTrue($parser->supports($match));
            }
        }

        foreach ($rgbaFailures as $channelFailures) {
            foreach ($channelFailures as $failure) {
                $this->assertFalse($parser->supports($failure));
            }
        }
    }

    /** @test */
    function it_correctly_parses_rgba_strings()
    {
        $parser = new RgbaParser;
        $supported = ['rgba(255, 255, 255, 0.5)', 'rgba(100%, 100%, 100%, 0.5)'];

        foreach ($supported as $color) {
            $this->assertEquals(
                ['red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 0.5],
                $parser->parse($color)
            );
        }
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $parser = new RgbaParser;
        $parser->parse('rgba(255, 100%, 100%, 0.5)');
    }
}
