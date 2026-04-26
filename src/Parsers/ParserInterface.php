<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @return array
     */
    public function parse(string $color) : array;

    /**
     * @return bool
     */
    public function supports(string $color) : bool;
}
