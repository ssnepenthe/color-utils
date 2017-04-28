<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\HexParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HexParserTest extends TestCase
{
    /** @test */
    function it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new HexParser;

        foreach (['#aabbcc', '#AABBCC', '#abc', '#ABC'] as $supported) {
            $this->assertTrue($parser->supports($supported));
        }

        $unsupported = [
            // No leading hash.
            'abc',
            // Invalid length.
            '#aa',
            // Invalid length.
            '#aabbc',
            // Invalid length.
            '#aabbccd',
            // Non-hex characters.
            '#gghhii'
        ];

        foreach ($unsupported as $hex) {
            $this->assertFalse($parser->supports($hex));
        }
    }

    /** @test */
    function it_correctly_parses_rgb_hex_strings()
    {
        $parser = new HexParser;

        foreach (['#abc', '#aabbcc'] as $hex) {
            $this->assertEquals(
                ['red' => 170, 'green' => 187, 'blue' => 204],
                $parser->parse($hex)
            );
        }
    }

    /** @test */
    function it_correctly_parses_rgba_hex_strings()
    {
        $parser = new HexParser;

        $this->assertEquals(
            ['red' => 170, 'green' => 187, 'blue' => 204, 'alpha' => 0.86667],
            $parser->parse('#aabbccdd')
        );
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $parser = new HexParser;
        $parser->parse('rgb(1, 2, 3)');
    }
}
