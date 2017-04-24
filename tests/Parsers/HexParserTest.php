<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\HexParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class HexParserTest extends TestCase
{
    public function test_it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new HexParser;

        foreach (['#aabbcc', '#AABBCC', '#abc', '#ABC'] as $supported) {
            $this->assertTrue($parser->supports($supported));
        }

        // Each item fails a different condition of ->supports().
        foreach (['abc', '#abcd', '#gghhii'] as $unsupported) {
            $this->assertFalse($parser->supports($unsupported));
        }
    }

    public function test_it_correctly_parses_hex_strings()
    {
        $parser = new HexParser;

        foreach (['#abc', '#aabbcc'] as $hex) {
            $this->assertEquals(
                ['red' => 170, 'green' => 187, 'blue' => 204],
                $parser->parse($hex)
            );
        }

        try {
            $parser->parse('rgb(1, 2, 3)');

            $this->fail(
                'HexParser::parse() throws exception when attempting to parse unsupported string'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
