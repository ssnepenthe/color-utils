<?php

namespace SSNepenthe\ColorUtils;

class Hsl implements ColorInterface
{
    use RangeableTrait;

    protected $alpha;
    protected $hue;
    protected $lightness;
    protected $saturation;

    protected $withAlpha = false;

    public function __construct(...$args)
    {
        $alpha = 1.0;

        if (4 === count($args)) {
            $this->withAlpha = true;
            $alpha = array_pop($args);
        }

        if (3 !== count($args)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        if (! is_numeric($alpha)) {
            // @todo
            throw new \InvalidArgumentException;
        }

        $hue = $this->shiftIntoRange(intval(round(floatval($args[0]))), 0, 360);

        list($saturation, $lightness) = array_map(function ($val) : int {
            return $this->forceIntoRange(intval(round(floatval($val))), 0, 100);
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

        $values = [
            (string) $this->hue,
            (string) $this->saturation . '%',
            (string) $this->lightness . '%',
        ];

        if ($this->withAlpha) {
            $type .= 'a';
            $values[] = (string) $this->alpha;
        }

        return sprintf('%s(%s)', $type, implode(', ', $values));
    }

    public static function fromHslString(string $hsl) : self
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

        $hue = $hsl[0];
        $saturation = $hsl[1];
        $lightness = $hsl[2];
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

        $hsl = array_map(function (string $val) : int {
            return intval(trim($val, '%'));
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
        return $this->alpha;
    }

    public function getHue() : int
    {
        return $this->hue;
    }

    public function getLightness() : int
    {
        return $this->lightness;
    }

    public function getSaturation() : int
    {
        return $this->saturation;
    }

    public function hasAlpha() : bool
    {
        return $this->withAlpha;
    }

    public function toArray() : array
    {
        $values = [$this->hue, $this->saturation, $this->lightness];

        if ($this->withAlpha) {
            $values[] = $this->alpha;
        }

        return $values;
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

        if ($this->withAlpha) {
            $defaults['alpha'] = $this->alpha;
        }

        $attrs = array_merge($defaults, $attrs);

        $args = [$attrs['hue'], $attrs['saturation'], $attrs['lightness']];

        if (isset($attrs['alpha'])) {
            $args[] = $attrs['alpha'];
        }

        return new Hsl(...$args);
    }

    protected static function isPercentageString(string $string) : bool
    {
        return false !== strpos($string, '%');
    }
}
