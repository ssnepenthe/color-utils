<?php

namespace SSNepenthe\ColorUtils;

class Rgb implements ColorInterface
{
    use RangeableTrait;

    protected $alpha;
    protected $blue;
    protected $green;
    protected $red;

    protected $withAlpha = false;

    public function __construct(...$args)
    {
        $a = 1.0;

        if (4 === count($args)) {
            $this->withAlpha = true;
            $a = array_pop($args);
        }

        if (3 !== count($args)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        if (! is_numeric($a)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $args = array_map(function ($value) : int {
            return $this->forceIntoRange(intval(round(floatval($value))), 0, 255);
        }, $args);

        $args[] = $this->forceIntoRange(floatval($a), 0.0, 1.0);

        list($this->red, $this->green, $this->blue, $this->alpha) = $args;
    }

    public function __toString() : string
    {
        $type = 'rgb';

        if ($this->withAlpha) {
            $type .= 'a';
        }

        return sprintf('%s(%s)', $type, implode(', ', $this->toArray()));
    }

    public static function fromHexString(string $hex) : self
    {
        if (1 === preg_match('/[^a-f0-9#]/i', $hex) || '#' !== substr($hex, 0, 1)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $hex = ltrim($hex, '#');

        switch (strlen($hex)) {
            case 3:
            case 4:
                $rgb = array_map(function (string $byte) : int {
                    return hexdec($byte . $byte);
                }, str_split($hex, 1));
                break;
            case 6:
            case 8:
                $rgb = array_map('hexdec', str_split($hex, 2));
                break;
            default:
                // @todo
                throw new \InvalidArgumentException;
        }

        if (4 === count($rgb)) {
            // Convert alpha to percentage.
            $rgb[3] = floatval($rgb[3] / 255);
        }

        return new static(...$rgb);
    }

    public static function fromKeyword(string $keyword) : self
    {
        if (! array_key_exists($keyword, ColorKeywords::MAP)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        return static::fromHexString(ColorKeywords::MAP[$keyword]);
    }

    public static function fromRgbString(string $rgb) : self
    {
        // Strip spaces.
        $rgb = str_replace(' ', '', $rgb);

        // {5,} quantifer is far from perfect but meant for 3 digits and 2 commas.
        if (! preg_match('/^rgba?\(([\d%,\.]{5,})\)$/i', $rgb, $matches)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        // Get matches and filter out empty strings.
        $rgb = explode(',', $matches[1]);
        $rgb = array_values(array_filter($rgb, function (string $val) : bool {
            return '' !== $val;
        }));
        $count = count($rgb);

        if (3 !== $count && 4 !== $count) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $colors = array_slice($rgb, 0, 3);
        $alpha = $rgb[3] ?? false;
        $alphaAsPercentage = is_string($alpha) && false !== strpos($alpha, '%');

        // It is all or none when using percentages for red, green and blue.
        $colorAsPercentage = array_filter(array_map(function (string $value) : bool {
            return false !== strpos($value, '%');
        }, $colors));

        if (! empty($colorAsPercentage) && 3 !== count($colorAsPercentage)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        // Fractions are not allowed for rgb color values.
        $colorAsFraction = array_filter(array_map(function (string $value) : bool {
            return false !== strpos($value, '.');
        }, $colors));

        if (! empty($colorAsFraction)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $colors = array_map(function (string $value) : int {
            $percent = false !== strpos($value, '%');

            $value = intval(trim($value, '%'));

            if ($percent) {
                $value = ($value / 100) * 255;
            }

            return $value;
        }, $colors);

        if ($alphaAsPercentage) {
            $colors[] = floatval(trim($alpha, '%')) / 100;
        } elseif (is_string($alpha)) {
            $colors[] = floatval($alpha);
        }

        return new static(...$colors);
    }

    public function getAlpha() : float
    {
        return $this->alpha;
    }

    public function getAlphaByte() : string
    {
        return $this->intToHexByte($this->alpha * 255);
    }

    public function getBlue() : int
    {
        return $this->blue;
    }

    public function getBlueByte() : string
    {
        return $this->intToHexByte($this->blue);
    }

    public function getGreen() : int
    {
        return $this->green;
    }

    public function getGreenByte() : string
    {
        return $this->intToHexByte($this->green);
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
        return $this->intToHexByte($this->red);
    }

    public function hasAlpha() : bool
    {
        return $this->withAlpha;
    }

    public function toArray() : array
    {
        $values = [$this->red, $this->green, $this->blue];

        if ($this->withAlpha) {
            $values[] = $this->alpha;
        }

        return $values;
    }

    public function toHexArray() : array
    {
        $values = [$this->getRedByte(), $this->getGreenByte(), $this->getBlueByte()];

        if ($this->withAlpha) {
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
            // @todo
            throw new \InvalidArgumentException;
        }

        // Merge defaults.
        $defaults = [
            'red' => $this->red,
            'green' => $this->green,
            'blue' => $this->blue,
        ];

        if ($this->withAlpha) {
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

    protected function intToHexByte(int $int) : string
    {
        return str_pad(dechex($int), 2, '0', STR_PAD_LEFT);
    }
}
