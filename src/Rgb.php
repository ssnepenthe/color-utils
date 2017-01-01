<?php

namespace SSNepenthe\ColorUtils;

use InvalidArgumentException;

class Rgb implements ColorInterface
{
    use RangeableTrait;

    protected $alpha;
    protected $blue;
    protected $green;
    protected $red;

    protected $hasAlpha = false;

    public function __construct(...$args)
    {
        array_walk($args, function ($arg) {
            if (! is_numeric($arg)) {
                throw new InvalidArgumentException(
                    'Rgb::__construct() args must be numeric'
                );
            }
        });

        $alpha = 1.0;

        if (4 === count($args)) {
            $this->hasAlpha = true;
            $alpha = array_pop($args);
        }

        if (3 !== count($args)) {
            throw new InvalidArgumentException(
                'Rgb::__construct() must be called with 3 or 4 args'
            );
        }

        $args = array_map(function ($value) {
            return $this->forceIntoRange(intval(round($value)), 0, 255);
        }, $args);

        $args[] = $this->forceIntoRange(floatval($alpha), 0.0, 1.0);

        list($this->red, $this->green, $this->blue, $this->alpha) = $args;
    }

    public function __toString() : string
    {
        $type = 'rgb';
        $values = $this->toArray();

        if ($this->hasAlpha()) {
            $type .= 'a';
            $values[3] = rtrim(number_format($values[3], 2), '0.');
        }

        return sprintf('%s(%s)', $type, implode(', ', $values));
    }

    public static function fromString(string $color) : ColorInterface
    {
        if ('#' === substr($color, 0, 1)) {
            return static::fromHexString($color);
        }

        if ('rgb' === substr($color, 0, 3)) {
            return static::fromRgbString($color);
        }

        return static::fromKeyword($color);
    }

    public function getAlpha() : float
    {
        return $this->alpha;
    }

    /**
     * @todo Should alpha be rounded before conversion like this? Or left alone?
     */
    public function getAlphaByte() : string
    {
        return $this->intToHexByte(intval(round($this->alpha * 255)));
    }

    public function getBlue() : int
    {
        return $this->blue;
    }

    public function getBlueByte() : string
    {
        return $this->intToHexByte($this->getBlue());
    }

    public function getGreen() : int
    {
        return $this->green;
    }

    public function getGreenByte() : string
    {
        return $this->intToHexByte($this->getGreen());
    }

    public function getName() : string
    {
        if ($name = array_search($this->toHexString(), ColorKeywords::MAP)) {
            return $name;
        }

        return '';
    }

    public function getRed() : int
    {
        return $this->red;
    }

    public function getRedByte() : string
    {
        return $this->intToHexByte($this->getRed());
    }

    public function hasAlpha() : bool
    {
        return $this->hasAlpha;
    }

    public function toArray() : array
    {
        $values = [$this->getRed(), $this->getGreen(), $this->getBlue()];

        if ($this->hasAlpha()) {
            $values[] = $this->getAlpha();
        }

        return $values;
    }

    public function toColor() : Color
    {
        return new Color($this);
    }

    public function toHexArray() : array
    {
        $values = [$this->getRedByte(), $this->getGreenByte(), $this->getBlueByte()];

        if ($this->hasAlpha()) {
            $values[] = $this->getAlphaByte();
        }

        return $values;
    }

    public function toHexString() : string
    {
        return '#' . implode('', $this->toHexArray());
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    public function with(array $attrs) : ColorInterface
    {
        $props = array_keys($attrs);

        // You must provide at least one of red, green, blue or alpha.
        if (empty(array_intersect(['red', 'green', 'blue', 'alpha'], $props))) {
            throw new InvalidArgumentException(
                'One of red, green, blue or alpha is required'
            );
        }

        // Merge defaults.
        $defaults = [
            'red' => $this->red,
            'green' => $this->green,
            'blue' => $this->blue,
        ];

        if ($this->hasAlpha()) {
            $defaults['alpha'] = $this->alpha;
        }

        $attrs = array_merge($defaults, $attrs);

        // Get just the attrs we want.
        $args = [$attrs['red'], $attrs['green'], $attrs['blue']];

        if (isset($attrs['alpha'])) {
            $args[] = $attrs['alpha'];
        }

        return new Rgb(...$args);
    }

    protected static function fromHexString(string $hex) : ColorInterface
    {
        if (1 === preg_match('/[^a-f0-9#]/i', $hex) || '#' !== substr($hex, 0, 1)) {
            throw new InvalidArgumentException('Invalid hex string provided');
        }

        $hex = ltrim($hex, '#');

        switch (strlen($hex)) {
            case 3:
            case 4:
                $rgb = array_map(function (string $byte) {
                    return hexdec($byte . $byte);
                }, str_split($hex, 1));
                break;
            case 6:
            case 8:
                $rgb = array_map(function (string $byte) {
                    return hexdec($byte);
                }, str_split($hex, 2));
                break;
            default:
                throw new InvalidArgumentException(
                    'Hex string must be 3, 4, 6 or 8 characters in length'
                );
        }

        if (4 === count($rgb)) {
            // Convert alpha to percentage.
            $rgb[3] = $rgb[3] / 255;
        }

        return new static(...$rgb);
    }

    protected static function fromKeyword(string $keyword) : ColorInterface
    {
        if (! array_key_exists($keyword, ColorKeywords::MAP)) {
            throw new InvalidArgumentException('Invalid keyword provided');
        }

        return static::fromHexString(ColorKeywords::MAP[$keyword]);
    }

    protected static function fromRgbString(string $rgb) : ColorInterface
    {
        // Strip spaces.
        $rgb = str_replace(' ', '', $rgb);

        // {5,} quantifer is far from perfect but meant for 3 digits and 2 commas.
        if (! preg_match('/^rgba?\(([\d%,\.]{5,})\)$/i', $rgb, $matches)) {
            throw new InvalidArgumentException('Invalid RGB string provided');
        }

        // Get matches and filter out empty strings.
        $rgb = explode(',', $matches[1]);
        $rgb = array_values(array_filter($rgb, function (string $val) : bool {
            return '' !== $val;
        }));
        $count = count($rgb);

        if (3 !== $count && 4 !== $count) {
            throw new InvalidArgumentException(
                'Rgb::fromRgbString() must be called with 3 or 4 args'
            );
        }

        $colors = array_slice($rgb, 0, 3);
        $alpha = $rgb[3] ?? false;

        $colorAsPercentage = array_filter(array_map(function (string $value) : bool {
            return false !== strpos($value, '%');
        }, $colors));

        if (! empty($colorAsPercentage) && 3 !== count($colorAsPercentage)) {
            throw new InvalidArgumentException(
                'Rgb::fromRgbString() must be called with all or none as percentages'
            );
        }

        $colorAsFraction = array_filter(array_map(function (string $value) : bool {
            return false !== strpos($value, '.');
        }, $colors));

        if (! empty($colorAsFraction)) {
            throw new InvalidArgumentException(
                'Fractions not allowed for RGB colors'
            );
        }

        $colors = array_map(function (string $value) {
            $percent = false !== strpos($value, '%');

            $value = intval(trim($value, '%'));

            if ($percent) {
                $value = intval(round(($value / 100) * 255));
            }

            return $value;
        }, $colors);

        if (is_string($alpha) && false !== strpos($alpha, '%')) {
            $colors[] = floatval(trim($alpha, '%')) / 100;
        } elseif (is_string($alpha)) {
            $colors[] = floatval($alpha);
        }

        return new static(...$colors);
    }

    protected function intToHexByte(int $int) : string
    {
        return str_pad(dechex($int), 2, '0', STR_PAD_LEFT);
    }
}
