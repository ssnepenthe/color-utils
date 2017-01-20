<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserResolverInterface
 */
interface ParserResolverInterface
{
    /**
     * @param string $color
     * @return ParserInterface|bool
     */
    public function resolve(string $color);
}
