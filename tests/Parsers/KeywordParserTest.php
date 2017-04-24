<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\HexParser;
use SSNepenthe\ColorUtils\Parsers\KeywordParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class KeywordParserTest extends TestCase
{
    public function test_it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new KeywordParser($this->createMock(HexParser::class));

        $this->assertTrue($parser->supports('black'));
        $this->assertTrue($parser->supports('BLACK'));
        $this->assertFalse($parser->supports('notarealcolor'));
    }

    public function test_it_correctly_delegates_to_hex_parser()
    {
        $hexParserStub = $this->createMock(HexParser::class);
        $hexParserStub->method('parse')
            ->with('#000000')
            ->willReturn(['red' => 0, 'green' => 0, 'blue' => 0]);

        $parser = new KeywordParser($hexParserStub);

        foreach (['black', 'BLACK'] as $color) {
            $this->assertEquals(
                ['red' => 0, 'green' => 0, 'blue' => 0],
                $parser->parse($color)
            );
        }

        try {
            $parser->parse('notarealcolor');

            $this->fail(
                'KeywordParser::parse() throws exception when attempting to parse unsupported string'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
