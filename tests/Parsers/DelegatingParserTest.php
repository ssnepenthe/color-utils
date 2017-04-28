<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\ParserInterface;
use SSNepenthe\ColorUtils\Parsers\DelegatingParser;
use SSNepenthe\ColorUtils\Parsers\ParserResolverInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class DelegatingParserTest extends TestCase
{
    /** @test */
    function it_is_instantiable()
    {
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $parser = new DelegatingParser($resolverStub);

        $this->assertInstanceOf(ParserInterface::class, $parser);
    }

    /** @test */
    function it_correctly_delegates_to_resolver_to_check_support()
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
    function it_correctly_delegates_to_resolver_to_parse_colors()
    {
        $toParse = '#abcdef';
        $parsed = ['red' => 173, 'green' => 205, 'blue' => 239];
        $parserStub = $this->createMock(ParserInterface::class);
        $parserStub->method('parse')
            ->with($toParse)
            ->willReturn($parsed);
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $resolverStub->method('resolve')
            ->with($toParse)
            ->willReturn($parserStub);

        $parser = new DelegatingParser($resolverStub);

        $this->assertEquals($parsed, $parser->parse($toParse));
    }

    /** @test */
    function it_throws_when_attempting_to_parse_unsupported_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $toParse = 'rgb(120, 120, 120)';
        $resolverStub = $this->createMock(ParserResolverInterface::class);
        $resolverStub->method('resolve')
            ->with($toParse)
            ->willReturn(false);

        $parser = new DelegatingParser($resolverStub);
        $parser->parse($toParse);
    }
}
