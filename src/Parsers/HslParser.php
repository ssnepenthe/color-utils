<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class HslParser
 */
class HslParser extends PatternParser
{
    /**
     * @return string
     */
    protected function getPattern() : string
    {
        return '/^hsl\(
            (?<hue>\d{1,3}),\s*
            (?<saturation>\d{1,3}%),\s*
            (?<lightness>\d{1,3}%)
        \)$/ix';
    }

    /**
     * @return array
     */
    protected function prepareExtractedData(array $data) : array
    {
        return array_map(fn($value): float => floatval(trim($value, '%')), $data);
    }
}
