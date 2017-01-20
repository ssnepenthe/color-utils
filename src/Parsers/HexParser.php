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
        $isShorthand = 3 === strlen($color);

        list($red, $green, $blue) = array_map(
            function ($value) use ($isShorthand) : int {
                return hexdec(str_repeat($value, $isShorthand ? 2 : 1));
            },
            str_split($color, $isShorthand ? 1 : 2)
        );

        return compact('red', 'green', 'blue');
    }

    /**
     * @param string $color
     * @return bool
     */
    public function supports(string $color) : bool
    {
        $len = strlen($color);

        // Need to keep an eye on alpha-hex notation support, update as appropriate.
        // http://caniuse.com/#feat=css-rrggbbaa
        return '#' === substr($color, 0, 1)
            && (4 === $len || 7 === $len)
            && ! (bool) preg_match('/[^a-f0-9#]/i', $color);
    }
}
