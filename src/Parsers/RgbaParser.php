<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class RgbaParser
 */
class RgbaParser extends RgbParser
{
    /**
     * @return string
     */
    protected function getPattern() : string
    {
        return '/^rgba\(
            (?<red>\d{1,3}(%)?),\s*
            (?<green>\d{1,3}(?(2)%)),\s*
            (?<blue>\d{1,3}(?(2)%)),\s*
            (?<alpha>(1|0)(\.)?(?(7)\d{1,5}))
        \)$/ix';
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareExtractedData(array $data) : array
    {
        // Red, green and blue channels.
        $rgba = parent::prepareExtractedData(array_slice($data, 0, 3));

        // Don't forget about alpha.
        $rgba['alpha'] = floatval($data['alpha']);

        return $rgba;
    }
}
