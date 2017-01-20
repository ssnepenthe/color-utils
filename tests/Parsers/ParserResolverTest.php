<?php

use SSNepenthe\ColorUtils\Parsers\ParserResolver;
use SSNepenthe\ColorUtils\Parsers\ParserInterface;
use SSNepenthe\ColorUtils\Parsers\ParserResolverInterface;

class ParserResolverTest extends PHPUnit_Framework_TestCase
{
    const PARSER = ParserInterface::class;

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_it_is_intantiable()
    {
        // Resolver accepts array of ParserInterfaces.
        $resolver = new ParserResolver([Mockery::mock(self::PARSER)]);

        // No type error.
        $this->assertInstanceOf(ParserResolverInterface::class, $resolver);
    }

    public function test_it_correctly_resolves_parser_based_on_support()
    {
        $hex = '#abcdef';

        $parser1 = Mockery::mock(self::PARSER)
            ->shouldReceive('supports')
            ->with($hex)
            ->twice()
            ->andReturn(false)
            ->getMock();
        $parser2 = Mockery::mock(self::PARSER)
            ->shouldReceive('supports')
            ->with($hex)
            ->twice()
            ->andReturn(false)
            ->getMock();
        $parser3 = Mockery::mock(self::PARSER)
            ->shouldReceive('supports')
            ->with($hex)
            ->once()
            ->andReturn(true)
            ->getMock();

        $resolver1 = new ParserResolver([$parser1, $parser2]);
        $resolver2 = new ParserResolver([$parser1, $parser2, $parser3]);

        $this->assertFalse($resolver1->resolve($hex));
        $this->assertSame($parser3, $resolver2->resolve($hex));
    }
}
