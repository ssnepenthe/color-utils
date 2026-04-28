<?php

namespace SSNepenthe\ColorUtils\Parsers;

use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class PatternParser
 */
abstract class PatternParser implements ParserInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function parse(string $color) : array
    {
        if (! (bool) preg_match($this->getPattern(), $color, $matches)) {
            throw new InvalidArgumentException(sprintf(
                'String %s not supported in %s',
                $color,
                __METHOD__
            ));
        }

        // Filter out numerically indexed values.
        $matches = array_filter($matches, fn($key): bool => is_string($key), ARRAY_FILTER_USE_KEY);

        return $this->prepareExtractedData($matches);
    }

    public function supports(string $color) : bool
    {
        return (bool) preg_match($this->getPattern(), $color);
    }

    abstract protected function getPattern() : string;

    abstract protected function prepareExtractedData(array $data) : array;
}
