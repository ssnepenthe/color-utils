<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserResolverInterface
 */
interface ParserResolverInterface
{
    /**
     * @param string $color
     * @return ParserInterface|false
     */
    public function resolve(string $color);
}
