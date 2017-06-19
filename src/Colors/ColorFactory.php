<?php

namespace SSNepenthe\ColorUtils\Colors;

use SSNepenthe\ColorUtils\Parsers\DelegatingParser;
use function SSNepenthe\ColorUtils\value_is_between;
use SSNepenthe\ColorUtils\Parsers\ParserResolverFactory;
use function SSNepenthe\ColorUtils\array_contains_all_of;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class ColorFactory
 */
class ColorFactory
{
    /**
     * @param array $channels
     * @return Color
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $channels) : Color
    {
        extract($channels);

        if (array_contains_all_of($channels, ['red', 'green', 'blue', 'alpha'])) {
            return static::fromRgba($red, $green, $blue, $alpha);
        }

        if (array_contains_all_of($channels, ['red', 'green', 'blue'])) {
            return static::fromRgb($red, $green, $blue);
        }

        if (array_contains_all_of(
            $channels,
            ['hue', 'saturation', 'lightness', 'alpha']
        )) {
            return static::fromHsla($hue, $saturation, $lightness, $alpha);
        }

        if (array_contains_all_of($channels, ['hue', 'saturation', 'lightness'])) {
            return static::fromHsl($hue, $saturation, $lightness);
        }

        throw new InvalidArgumentException(sprintf(
            'Complete color representation array required in %s',
            __METHOD__
        ));
    }

    /**
     * @param float $hue
     * @param float $saturation
     * @param float $lightness
     * @param float $alpha
     * @return Color
     */
    public static function fromHsla($hue, $saturation, $lightness, $alpha) : Color
    {
        return new Color(new Hsla($hue, $saturation, $lightness, $alpha));
    }

    /**
     * @param float $hue
     * @param float $saturation
     * @param float $lightness
     * @return Color
     */
    public static function fromHsl($hue, $saturation, $lightness) : Color
    {
        return new Color(new Hsl($hue, $saturation, $lightness));
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param float $alpha
     * @return Color
     */
    public static function fromRgba($red, $green, $blue, $alpha) : Color
    {
        return new Color(new Rgba($red, $green, $blue, $alpha));
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return Color
     */
    public static function fromRgb($red, $green, $blue) : Color
    {
        return new Color(new Rgb($red, $green, $blue));
    }

    /**
     * @param string $color
     * @return Color
     */
    public static function fromString(string $color) : Color
    {
        $parser = new DelegatingParser(ParserResolverFactory::all());

        return static::fromArray($parser->parse($color));
    }

    /**
     * @param mixed ...$args
     * @return Color
     * @throws InvalidArgumentException
     */
    public static function fromUnknown(...$args) : Color
    {
        $count = count($args);

        if (1 === $count) {
            return static::fromUnknownOneArg(...$args);
        }

        if (3 === $count) {
            return static::fromUnknownThreeArgs(...$args);
        }

        if (4 === $count) {
            return static::fromUnknownFourArgs(...$args);
        }

        throw new InvalidArgumentException(sprintf(
            'Unrecognized arguments given in %s',
            __METHOD__
        ));
    }

    /**
     * @param mixed $one
     * @param mixed $two
     * @param mixed $three
     * @param mixed $four
     * @return Color
     * @throws InvalidArgumentException
     */
    public static function fromUnknownFourArgs($one, $two, $three, $four) : Color
    {
        if (static::couldBeRgbArgs($one, $two, $three, $four)) {
            return static::fromRgba($one, $two, $three, $four);
        }

        if (static::couldBeHslArgs($one, $two, $three, $four)) {
            return static::fromHsla($one, $two, $three, $four);
        }

        throw new InvalidArgumentException(sprintf(
            'Unrecognized arguments given in %s',
            __METHOD__
        ));
    }

    /**
     * @param mixed $one
     * @return Color
     * @throws InvalidArgumentException
     */
    public static function fromUnknownOneArg($one) : Color
    {
        if ($one instanceof Color) {
            return $one;
        }

        if ($one instanceof ColorInterface) {
            return new Color($one);
        }

        if (is_array($one)) {
            return static::fromArray($one);
        }

        if (is_string($one)) {
            return static::fromString($one);
        }

        throw new InvalidArgumentException(sprintf(
            'Unrecognized arguments given in %s',
            __METHOD__
        ));
    }

    /**
     * @param mixed $one
     * @param mixed $two
     * @param mixed $three
     * @return Color
     * @throws InvalidArgumentException
     */
    public static function fromUnknownThreeArgs($one, $two, $three) : Color
    {
        if (static::couldBeRgbArgs($one, $two, $three)) {
            return static::fromRgb($one, $two, $three);
        }

        if (static::couldBeHslArgs($one, $two, $three)) {
            return static::fromHsl($one, $two, $three);
        }

        throw new InvalidArgumentException(sprintf(
            'Unrecognized arguments given in %s',
            __METHOD__
        ));
    }

    /**
     * @param mixed ...$args
     * @return bool
     */
    protected static function couldBeHslArgs(...$args) : bool
    {
        return (3 <= count($args) && (
            value_is_between($args[0], 0, 360)
            && value_is_between($args[1], 0, 100)
            && value_is_between($args[2], 0, 100)
        ));
    }

    /**
     * @param mixed ...$args
     * @return bool
     */
    protected static function couldBeRgbArgs(...$args) : bool
    {
        return (3 <= count($args) && (
            value_is_between($args[0], 0, 255)
            && value_is_between($args[1], 0, 255)
            && value_is_between($args[2], 0, 255)
        ));
    }
}
