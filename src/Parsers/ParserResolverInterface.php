<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserResolverInterface
 */
interface ParserResolverInterface
{
    /**
     * @return ParserInterface|false
     */
    public function resolve(string $color);
}
