<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class ParserResolverFactory
 */
class ParserResolverFactory
{
    public static function all() : ParserResolver
    {
        $hexParser = new HexParser;

        return new ParserResolver([
            $hexParser,
            new KeywordParser($hexParser),
            new RgbaParser,
            new RgbParser,
            new HslaParser,
            new HslParser,
        ]);
    }

    public static function rgb() : ParserResolver
    {
        $hexParser = new HexParser;

        return new ParserResolver([
            $hexParser,
            new KeywordParser($hexParser),
            new RgbaParser,
            new RgbParser,
        ]);
    }

    public static function hsl() : ParserResolver
    {
        return new ParserResolver([
            new HslaParser,
            new HslParser,
        ]);
    }
}
