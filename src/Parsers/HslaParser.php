<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class HslaParser
 */
class HslaParser extends HslParser
{
    /**
     * @return string
     */
    protected function getPattern() : string
    {
        return '/^hsla\(
            (?<hue>\d{1,3}(\.)?(?(2)\d{1,5})),\s*
            (?<saturation>\d{1,3}(\.)?(?(4)\d{1,5})%),\s*
            (?<lightness>\d{1,3}(\.)?(?(6)\d{1,5})%),\s*
            (?<alpha>(1|0)(\.)?(?(9)\d{1,5}))
        \)$/ix';
    }
}
