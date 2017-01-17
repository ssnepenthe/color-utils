<?php

namespace SSNepenthe\ColorUtils;

use InvalidArgumentException;

class Hsl implements ColorInterface
{
    protected $alpha;
    protected $hue;
    protected $lightness;
    protected $saturation;

    public function __construct(...$args)
    {
        array_walk($args, function ($arg) {
            if (! is_numeric($arg)) {
                throw new InvalidArgumentException(
                    'Hsl::__construct() must be called with numeric args'
                );
            }
        });

        $alpha = 1.0;

        if (4 === count($args)) {
            $alpha = array_pop($args);
        }

        if (3 !== count($args)) {
            throw new InvalidArgumentException(
                'Hsl::__construct() must be called with 3 or 4 arguments'
            );
        }

        $hue = modulo(floatval($args[0]), 360);

        list($saturation, $lightness) = array_map(function ($val) {
            return restrict(floatval($val), 0.0, 100.0);
        }, [$args[1], $args[2]]);

        $alpha = restrict(floatval($alpha), 0.0, 1.0);

        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->alpha = $alpha;
    }

    public function __toString() : string
    {
        $type = 'hsl';
        $values = $this->toArray();

        $values[1] = (string) $values[1] . '%';
        $values[2] = (string) $values[2] . '%';

        if ($this->hasAlpha()) {
            $type .= 'a';
            // Double trim prevent return of empty string from 0.00.
            $values[3] = rtrim(rtrim(number_format($values[3], 2), '0'), '.');
        }

        return sprintf('%s(%s)', $type, implode(', ', $values));
    }

    public static function fromString(string $hsl) : ColorInterface
    {
        $hsl = str_replace(' ', '', $hsl);

        if (! preg_match('/^hsla?\(([\d,%\.]{5,})\)$/i', $hsl, $matches)) {
            throw new InvalidArgumentException('Invalid HSL string provided');
        }

        // Get matches and filter out empty strings.
        $hsl = explode(',', $matches[1]);
        $hsl = array_values(array_filter($hsl, function (string $val) : bool {
            return '' !== $val;
        }));
        $count = count($hsl);

        if (3 !== $count && 4 !== $count) {
            throw new InvalidArgumentException(
                'Hsl::fromString() must be called with 3 or 4 arguments'
            );
        }

        list($hue, $saturation, $lightness) = array_slice($hsl, 0, 3);
        $alpha = $hsl[3] ?? false;

        if (static::isPercentageString($hue)) {
            throw new InvalidArgumentException(
                'Hue cannot be provided as a percentage'
            );
        }

        if (! static::isPercentageString($saturation) ||
            ! static::isPercentageString($lightness)
        ) {
            throw new InvalidArgumentException(
                'Saturation and Lightness must be provided as percentages'
            );
        }

        $hsl = array_map(function (string $val) {
            return floatval(trim($val, '%'));
        }, [$hue, $saturation, $lightness]);

        if (static::isPercentageString($alpha)) {
            $hsl[] = floatval(trim($alpha, '%')) / 100;
        } elseif (is_string($alpha)) {
            $hsl[] = floatval($alpha);
        }

        return new static(...$hsl);
    }

    public function getAlpha() : float
    {
        return round($this->alpha, 5);
    }

    public function getHue() : float
    {
        return round($this->hue, 5);
    }

    public function getLightness() : float
    {
        return round($this->lightness, 5);
    }

    public function getSaturation() : float
    {
        return round($this->saturation, 5);
    }

    public function hasAlpha() : bool
    {
        return 1.0 !== $this->alpha;
    }

    public function isDark($threshold = 50.0) : bool
    {
        $threshold = restrict(floatval($threshold), 0.0, 100.0);

        return $threshold > $this->getLightness();
    }

    public function isLight($threshold = 50.0) : bool
    {
        $threshold = restrict(floatval($threshold), 0.0, 100.0);

        return $threshold <= $this->getLightness();
    }

    public function toArray() : array
    {
        $values = [$this->getHue(), $this->getSaturation(), $this->getLightness()];

        if ($this->hasAlpha()) {
            $values[] = $this->getAlpha();
        }

        return $values;
    }

    public function toColor() : Color
    {
        return new Color($this);
    }

    public function toHsl() : Hsl
    {
        return $this;
    }

    public function toRgb() : Rgb
    {
        // 0) We want saturation and lightness on a scale of 0 - 1.
        $saturation = $this->saturation / 100;
        $lightness = $this->lightness / 100;

        // 1) No saturation means no hue means color is a shade of gray.
        if (0 === $saturation) {
            $colors = array_fill(0, 3, $lightness * 255);

            if ($this->hasAlpha()) {
                $colors[] = $this->getAlpha();
            }

            return new Rgb(...$colors);
        }

        // 2/3) Temporary vars.
        if ($lightness <= 0.5) {
            $temp1 = $lightness * (1 + $saturation);
        } else {
            $temp1 = $lightness + $saturation - $lightness * $saturation;
        }

        $temp2 = 2 * $lightness - $temp1;

        // 4) Get hue on a scale of 0 - 1.
        $hue = $this->hue / 360;

        // 5) Temporary colors.
        $tempColors = array_map(function ($colorValue) {
            return modulo($colorValue, 1);
        }, [$hue + (1 / 3), $hue, $hue - (1 / 3)]);

        // 6) Actual color values.
        $colors = array_map(function ($colorValue) use ($temp1, $temp2) {
            if (6 * $colorValue < 1) {
                return $temp2 + ($temp1 - $temp2) * 6 * $colorValue;
            }

            if (2 * $colorValue < 1) {
                return $temp1;
            }

            if (3 * $colorValue < 2) {
                return $temp2 + ($temp1 - $temp2) * ((2 / 3) - $colorValue) * 6;
            }

            return $temp2;
        }, $tempColors);

        // 7) Convert to 8-bit.
        $colors = array_map(function ($color) {
            return $color * 255;
        }, $colors);

        if ($this->hasAlpha()) {
            $colors[] = $this->getAlpha();
        }

        return new Rgb(...$colors);
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    public function with(array $attrs) : ColorInterface
    {
        // You must provide at least one of hue, saturation, lightness or alpha.
        if (! array_contains_one_of(
            $attrs,
            ['hue', 'saturation', 'lightness', 'alpha']
        )) {
            throw new InvalidArgumentException(
                'One of hue, saturation, lightness or alpha is required'
            );
        }

        // Merge defaults.
        $defaults = [
            'hue' => $this->hue,
            'saturation' => $this->saturation,
            'lightness' => $this->lightness,
        ];

        if ($this->hasAlpha()) {
            $defaults['alpha'] = $this->alpha;
        }

        $attrs = array_merge($defaults, $attrs);

        $args = [$attrs['hue'], $attrs['saturation'], $attrs['lightness']];

        if (isset($attrs['alpha'])) {
            $args[] = $attrs['alpha'];
        }

        return new Hsl(...$args);
    }

    protected static function isPercentageString($string) : bool
    {
        return is_string($string) && false !== strpos($string, '%');
    }
}
