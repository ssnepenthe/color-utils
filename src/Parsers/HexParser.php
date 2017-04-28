<?php

namespace SSNepenthe\ColorUtils\Parsers;

use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class HexParser
 */
class HexParser implements ParserInterface
{
    /**
     * @param string $color
     * @return array
     */
    public function parse(string $color) : array
    {
        if (! $this->supports($color)) {
            throw new InvalidArgumentException(sprintf(
                'String %s not supported in %s',
                $color,
                __METHOD__
            ));
        }

        $color = ltrim($color, '#');
        $len = strlen($color);
        $isShorthand = 3 === $len || 4 === $len;

        $values = array_map(function ($value) use ($isShorthand) : int {
            return hexdec($isShorthand ? str_repeat($value, 2) : $value);
        }, str_split($color, $isShorthand ? 1 : 2));

        $keys = ['red', 'green', 'blue'];

        if (4 === $len || 8 === $len) {
            $keys[] = 'alpha';
            $values[3] = round($values[3] / 255, 5);
        }

        return array_combine($keys, $values);
    }

    /**
     * @param string $color
     * @return bool
     */
    public function supports(string $color) : bool
    {
        $len = strlen($color);

        return '#' === substr($color, 0, 1)
            && (4 === $len || 5 === $len || 7 === $len || 9 === $len)
            && ! (bool) preg_match('/[^a-f0-9#]/i', $color);
    }
}
