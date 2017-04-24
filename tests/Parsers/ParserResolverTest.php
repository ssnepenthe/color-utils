<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Parsers\ParserResolver;
use SSNepenthe\ColorUtils\Parsers\ParserInterface;
use SSNepenthe\ColorUtils\Parsers\ParserResolverInterface;

class ParserResolverTest extends TestCase
{
    /** @test */
    function it_is_intantiable()
    {
        $resolver = new ParserResolver([$this->createMock(ParserInterface::class)]);

        $this->assertInstanceOf(ParserResolverInterface::class, $resolver);
    }

    /** @test */
    function it_correctly_resolves_parser_based_on_support()
    {
        $hex = '#abcdef';
        $firstParserStub = $this->createMock(ParserInterface::class);
        $firstParserStub->method('supports')
            ->with($hex)
            ->willReturn(false);
        $secondParserStub = $this->createMock(ParserInterface::class);
        $secondParserStub->method('supports')
            ->with($hex)
            ->willReturn(false);
        $thirdParserStub = $this->createMock(ParserInterface::class);
        $thirdParserStub->method('supports')
            ->with($hex)
            ->willReturn(true);

        $firstResolver = new ParserResolver([$firstParserStub, $secondParserStub]);
        $secondResolver = new ParserResolver([
            $firstParserStub,
            $secondParserStub,
            $thirdParserStub
        ]);

        $this->assertFalse($firstResolver->resolve($hex));
        $this->assertSame($thirdParserStub, $secondResolver->resolve($hex));
    }
}
