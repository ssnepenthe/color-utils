<?php

use SSNepenthe\ColorUtils\Parsers\ParserInterface;
use SSNepenthe\ColorUtils\Parsers\DelegatingParser;
use SSNepenthe\ColorUtils\Parsers\ParserResolverInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class DelegatingParserTest extends PHPUnit_Framework_TestCase
{
    const PARSER = ParserInterface::class;
    const RESOLVER = ParserResolverInterface::class;

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_it_is_instantiable()
    {
        // Delegating parser accepts parser resolver.
        $parser = new DelegatingParser(Mockery::mock(self::RESOLVER));

        // No type error.
        $this->assertInstanceOf(ParserInterface::class, $parser);
    }

    public function test_it_correctly_delegates_to_resolver_to_check_support()
    {
        $supported = '#abcdef';
        $unsupported = 'rgb(120, 120, 120)';
        $resolver = Mockery::mock(self::RESOLVER)
            ->shouldReceive('resolve')
            ->with($supported)
            ->once()
            ->andReturn(Mockery::mock(self::PARSER))
            ->shouldReceive('resolve')
            ->with($unsupported)
            ->once()
            ->andReturn(false)
            ->getMock();
        $parser = new DelegatingParser($resolver);

        $this->assertTrue($parser->supports($supported));
        $this->assertFalse($parser->supports($unsupported));
    }

    public function test_it_correctly_delegates_to_resolver_to_parse_colors()
    {
        $supported = '#abcdef';
        $parsedHex = ['red' => 173, 'green' => 205, 'blue' => 239];
        $unsupported = 'rgb(120, 120, 120)';

        $interface = Mockery::mock(self::PARSER)
            ->shouldReceive('parse')
            ->with($supported)
            ->once()
            ->andReturn($parsedHex)
            ->getMock();
        $resolver = Mockery::mock(self::RESOLVER)
            ->shouldReceive('resolve')
            ->with($supported)
            ->once()
            ->andReturn($interface)
            ->shouldReceive('resolve')
            ->with($unsupported)
            ->once()
            ->andReturn(false)
            ->getMock();
        $parser = new DelegatingParser($resolver);

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
