<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    public function parse(string $color) : array;

    public function supports(string $color) : bool;
}
