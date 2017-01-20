<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class ParserResolverFactory
 */
class ParserResolverFactory
{
    /**
     * @return ParserResolver
     */
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

    /**
     * @return ParserResolver
     */
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

    /**
     * @return ParserResolver
     */
    public static function hsl() : ParserResolver
    {
        return new ParserResolver([
            new HslaParser,
            new HslParser,
        ]);
    }
}
