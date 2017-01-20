<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @param string $color
     * @return array
     */
    public function parse(string $color) : array;

    /**
     * @param string $color
     * @return bool
     */
    public function supports(string $color) : bool;
}
