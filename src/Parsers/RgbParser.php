<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class RgbParser
 */
class RgbParser extends PatternParser
{
    /**
     * @return string
     */
    protected function getPattern() : string
    {
        return '/^rgb\(
            (?<red>\d{1,3}(%)?),\s*
            (?<green>\d{1,3}(?(2)%)),\s*
            (?<blue>\d{1,3}(?(2)%))
        \)$/ix';
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareExtractedData(array $data) : array
    {
        return array_map(function (string $channel) : int {
            // First check if string is in percent form so we can scale 0-255 later.
            $isPercent = false;

            if ('%' === substr($channel, -1)) {
                $isPercent = true;
            }

            $channel = intval(trim($channel, '%'));

            if ($isPercent) {
                $channel = intval(round($channel * 255 / 100));
            }

            return $channel;
        }, $data);
    }
}
