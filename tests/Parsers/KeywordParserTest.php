<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\HexParser;
use SSNepenthe\ColorUtils\Parsers\KeywordParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class KeywordParserTest extends TestCase
{
    /** @test */
    function it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new KeywordParser($this->createMock(HexParser::class));

        $this->assertTrue($parser->supports('black'));
        $this->assertTrue($parser->supports('BLACK'));
        $this->assertFalse($parser->supports('notarealcolor'));
    }

    /** @test */
    function it_correctly_delegates_to_hex_parser()
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
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);
        $hexParserMock = $this->createMock(HexParser::class);

        $parser = new KeywordParser($hexParserMock);
        $parser->parse('notarealcolor');
    }
}
