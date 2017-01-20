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
            (?<hue>\d{1,3}),\s*
            (?<saturation>\d{1,3}%),\s*
            (?<lightness>\d{1,3}%),\s*
            (?<alpha>(1|0)(\.)?(?(6)\d{1,5}))
        \)$/ix';
    }
}
