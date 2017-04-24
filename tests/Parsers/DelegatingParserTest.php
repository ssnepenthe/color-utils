<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\ParserInterface;
use SSNepenthe\ColorUtils\Parsers\DelegatingParser;
use SSNepenthe\ColorUtils\Parsers\ParserResolverInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class DelegatingParserTest extends TestCase
{
    /** @test */
    public function it_is_instantiable()
    {
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $parser = new DelegatingParser($resolverStub);

        $this->assertInstanceOf(ParserInterface::class, $parser);
    }

    /** @test */
    public function it_correctly_delegates_to_resolver_to_check_support()
    {
        $supported = '#abcdef';
        $unsupported = 'rgb(120, 120, 120)';
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $resolverStub->method('resolve')
            ->will($this->returnValueMap([
                [$supported, $this->createMock(ParserInterface::class)],
                [$unsupported, false],
            ]));
        $parser = new DelegatingParser($resolverStub);

        $this->assertTrue($parser->supports($supported));
        $this->assertFalse($parser->supports($unsupported));
    }

    /** @test */
    public function it_correctly_delegates_to_resolver_to_parse_colors()
    {
        $supported = '#abcdef';
        $unsupported = 'rgb(120, 120, 120)';
        $parsedHex = ['red' => 173, 'green' => 205, 'blue' => 239];

        $parserStub = $this->createMock(ParserInterface::class);
        $parserStub->method('parse')
            ->willReturn($parsedHex);
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $resolverStub->method('resolve')
            ->will($this->returnValueMap([
                [$supported, $parserStub],
                [$unsupported, false],
            ]));

        $parser = new DelegatingParser($resolverStub);

        $this->assertEquals($parsedHex, $parser->parse($supported));

        try {
            $parser->parse($unsupported);

            $this->fail(
                'DelegatingParser::parse() throws exception when attempting to parse unsupported string'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
