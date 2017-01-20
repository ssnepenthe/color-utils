<?php

use SSNepenthe\ColorUtils\Parsers\HexParser;
use SSNepenthe\ColorUtils\Parsers\KeywordParser;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class KeywordParserTest extends PHPUnit_Framework_TestCase
{
    const HEXPARSER = HexParser::class;

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_it_knows_whether_it_can_parse_a_given_string()
    {
        $parser = new KeywordParser(Mockery::mock(self::HEXPARSER));

        $this->assertTrue($parser->supports('black'));
        $this->assertTrue($parser->supports('BLACK'));
        $this->assertFalse($parser->supports('notarealcolor'));
    }

    public function test_it_correctly_parses_hex_strings()
    {
        $parser = new KeywordParser(
            Mockery::mock(self::HEXPARSER)
                ->shouldReceive('parse')
                ->with('#000000')
                ->twice()
                ->andReturn(['red' => 0, 'green' => 0, 'blue' => 0])
                ->getMock()
        );

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
