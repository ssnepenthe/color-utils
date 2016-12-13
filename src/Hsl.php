<?php

namespace SSNepenthe\ColorUtils;

class Hsl implements ColorInterface
{
    use RangeableTrait;

    protected $alpha;
    protected $hue;
    protected $lightness;
    protected $saturation;

    protected $hasAlpha = false;

    public function __construct(...$args)
    {
        array_walk($args, function($arg) {
            if (! is_numeric($arg)) {
                // @todo
                throw new \InvalidArgumentException;
            }
        });

        $alpha = 1.0;

        if (4 === count($args)) {
            $this->hasAlpha = true;
            $alpha = array_pop($args);
        }

        if (3 !== count($args)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $hue = $this->shiftIntoRange(floatval($args[0]), 0.0, 360.0);

        list($saturation, $lightness) = array_map(function ($val) : float {
            return $this->forceIntoRange(floatval($val), 0.0, 100.0);
        }, [$args[1], $args[2]]);

        $alpha = $this->forceIntoRange(floatval($alpha), 0.0, 1.0);

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

        if ($this->hasAlpha) {
            $type .= 'a';
            $values[3] = rtrim(number_format($values[3], 2), '0');
        }

        return sprintf('%s(%s)', $type, implode(', ', $values));
    }

    public static function fromString(string $hsl) : ColorInterface
    {
        $hsl = str_replace(' ', '', $hsl);

        if (! preg_match('/^hsla?\(([\d,%\.]{5,})\)$/i', $hsl, $matches)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        // Get matches and filter out empty strings.
        $hsl = explode(',', $matches[1]);
        $hsl = array_values(array_filter($hsl, function (string $val) : bool {
            return '' !== $val;
        }));
        $count = count($hsl);

        if (3 !== $count && 4 !== $count) {
            // @todo
            throw new \InvalidArgumentException;
        }

        list($hue, $saturation, $lightness) = array_slice($hsl, 0, 3);
        $alpha = $hsl[3] ?? false;

        if (static::isPercentageString($hue)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        if (! static::isPercentageString($saturation) ||
            ! static::isPercentageString($lightness)
        ) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $hsl = array_map(function (string $val) : float {
            return floatval(trim($val, '%'));
        }, [$hue, $saturation, $lightness]);

        if (static::isPercentageString($alpha)) {
            $hsl[] = floatval(trim($alpha, '%')) / 100.0;
        } elseif (is_string($alpha)) {
            $hsl[] = floatval($alpha);
        }

        return new static(...$hsl);
    }

    public function getAlpha() : float
    {
        return $this->alpha;
    }

    public function getHue() : int
    {
        return intval(round($this->hue));
    }

    public function getLightness() : int
    {
        return intval(round($this->lightness));
    }

    public function getSaturation() : int
    {
        return intval(round($this->saturation));
    }

    public function hasAlpha() : bool
    {
        return $this->hasAlpha;
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

    public function toString() : string
    {
        return $this->__toString();
    }

    public function with(array $attrs) : ColorInterface
    {
        $props = array_keys($attrs);

        // You must provide at least one of hue, saturation or lightness.
        $allowed = ['hue', 'saturation', 'lightness', 'alpha'];
        if (empty(array_intersect($allowed, $props))) {
            // @todo
            throw new \InvalidArgumentException;
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
