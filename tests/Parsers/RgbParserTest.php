<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\RgbParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class RgbParserTest extends TestCase
{
    /** @test */
    function it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new RgbParser;

        /*
        MATCHING CONDITIONS

        general: "rgb" prefix is required, not case sensitive, channels are
                 surrounded by parens, channels are delimited by ",", should match
                 with or without spacing between channels, all or none for percent,
                 fractions/decimals are not allowed for rgb values.
        red:     1 - 3 digits, can optionally be percent
        green:   1 - 3 digits, can be percent, but only if red is percent
        blue:    1 - 3 digits, can be percent, but only if red and green are percent
         */

        $matchingGeneral = [
            'rgb(120, 120, 120)',
            'RGB(120, 120, 120)',
            'rgb(120,120,120)',
            'rgb(100%, 100%, 100%)',
        ];
        $matchingRed = [
            'rgb(1, 0, 0)',
            'rgb(11, 0, 0)',
            'rgb(111, 0, 0)',
        ];
        $matchingGreen = [
            'rgb(0, 1, 0)',
            'rgb(0, 11, 0)',
            'rgb(0, 111, 0)',
        ];
        $matchingBlue = [
            'rgb(0, 0, 1)',
            'rgb(0, 0, 11)',
            'rgb(0, 0, 111)',
        ];

        $failingGeneral = [
            // Wrong prefix.
            'rgba(0, 0, 0)',
            // Wrong brackets.
            'rgb{0, 0, 0}',
            // Wrong delimiter.
            'rgb(0. 0. 0)',
            // Mixed int/percent in rgb.
            'rgb(0, 100%, 0)',
            // Fractions not allowed for rgb values.
            'rgb(0, 1.5, 0)',
        ];
        $failingRed = [
            // Non-numeric characters.
            'rgb(I00, 0, 0)',
            // Too many digits.
            'rgb(0000, 0, 0)',
        ];
        $failingGreen = [
            // Non-numeric characters.
            'rgb(0, I00, 0)',
            // Too many digits.
            'rgb(0, 0000, 0)',
        ];
        $failingBlue = [
            // Non-numeric characters.
            'rgb(0, 0, I00)',
            // Too many digits.
            'rgb(0, 0, 0000)',
        ];

        $rgbMatches = [
            $matchingGeneral,
            $matchingRed,
            $matchingGreen,
            $matchingBlue,
        ];

        $rgbFailures = [
            $failingGeneral,
            $failingRed,
            $failingGreen,
            $failingBlue,
        ];

        foreach ($rgbMatches as $channelMatches) {
            foreach ($channelMatches as $match) {
                $this->assertTrue($parser->supports($match));
            }
        }

        foreach ($rgbFailures as $channelFailures) {
            foreach ($channelFailures as $failure) {
                $this->assertFalse($parser->supports($failure));
            }
        }
    }

    /** @test */
    function it_correctly_parses_rgba_strings()
    {
        $parser = new RgbParser;

        foreach (['rgb(255, 255, 255)', 'rgb(100%, 100%, 100%)'] as $color) {
            $this->assertEquals(
                ['red' => 255, 'green' => 255, 'blue' => 255],
                $parser->parse($color)
            );
        }
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $parser = new RgbParser;
        $parser->parse('rgb(255, 100%, 100%)');
    }
}
