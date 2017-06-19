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
     * @throws InvalidArgumentException
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
        return $this->startsWithHash($color)
            && $this->isValidLength($color)
            && $this->containsOnlyHexCharacters($color);
    }

    /**
     * @param string $color
     * @return bool
     */
    protected function containsOnlyHexCharacters(string $color) : bool
    {
        return ! (bool) preg_match('/[^a-f0-9#]/i', $color);
    }

    /**
     * @param string $color
     * @return bool
     */
    protected function isValidLength(string $color) : bool
    {
        $len = strlen($color);

        return 4 === $len || 5 === $len || 7 === $len || 9 === $len;
    }

    /**
     * @param string $color
     * @return bool
     */
    protected function startsWithHash(string $color) : bool
    {
        return '#' === substr($color, 0, 1);
    }
}
