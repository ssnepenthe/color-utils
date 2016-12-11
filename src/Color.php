<?php

namespace SSNepenthe\ColorUtils;

class Color implements ColorInterface
{
    protected $hsl;
    protected $rgb;
    protected $type;

    public function __construct(
        ColorInterface $color,
        Converter $converter = null
    ) {
        $initialProp = $this->type = strtolower(
            (new \ReflectionClass($color))->getShortName()
        );

        $props = array_diff(['hsl', 'rgb'], [$initialProp]);

        $this->{$initialProp} = $color;
        $this->converter = is_null($converter) ? new Converter : $converter;

        $prop = reset($props);
        // I.e. hslToRgb.
        $converterMethod = sprintf('%sTo%s', $initialProp, ucfirst($prop));
        $this->{$prop} = $this->converter->{$converterMethod}($this->{$initialProp});
    }

    public function __toString() : string
    {
        return (string) $this->{$this->type};
    }

    public static function fromHex(string $hex) : self
    {
        return new static(Rgb::fromHexString($hex));
    }

    public static function fromHsl(...$args) : self
    {
        if (1 === count($args)) {
            return new static(Hsl::fromHslString(...$args));
        }

        return new static(new Hsl(...$args));
    }

    public static function fromKeyword(string $keyword) : self
    {
        return new static(Rgb::fromKeyword($keyword));
    }

    public static function fromRgb(...$args) : self
    {
        if (1 === count($args)) {
            return new static(Rgb::fromRgbString(...$args));
        }

        return new static(new Rgb(...$args));
    }

    public function getAlpha() : float
    {
        return $this->{$this->type}->getAlpha();
    }

    public function getBlue() : int
    {
        return $this->rgb->getBlue();
    }

    public function getGreen() : int
    {
        return $this->rgb->getGreen();
    }

    public function getHsl() : Hsl
    {
        return $this->hsl;
    }

    public function getHue() : int
    {
        return $this->hsl->getHue();
    }

    public function getLightness() : int
    {
        return $this->hsl->getLightness();
    }

    public function getName() : string
    {
        return $this->rgb->getName();
    }

    public function getPerceivedBrightness() : int
    {
        $weightedRed = 0.299 * pow($this->getRed() / 255, 2);
        $weightedGreen = 0.587 * pow($this->getGreen() / 255, 2);
        $weightedBlue = 0.114 * pow($this->getBlue() / 255, 2);

        return intval(100 * sqrt($weightedRed + $weightedGreen + $weightedBlue));
    }

    public function getRed() : int
    {
        return $this->rgb->getRed();
    }

    public function getRgb() : Rgb
    {
        return $this->rgb;
    }

    public function getSaturation() : int
    {
        return $this->hsl->getSaturation();
    }

    public function getType() : string
    {
        return $this->type;
    }

    /**
     * No real need to check both but, hey, why not?
     */
    public function hasAlpha() : bool
    {
        return $this->hsl->hasAlpha() && $this->rgb->hasAlpha();
    }

    public function isLight(int $threshold = 50) : bool
    {
        return $threshold <= $this->getLightness();
    }

    public function isDark(int $threshold = 50) : bool
    {
        return $threshold > $this->getLightness();
    }

    public function looksLight(int $threshold = 50) : bool
    {
        return $threshold <= $this->getPerceivedBrightness();
    }

    public function looksDark(int $threshold = 50) : bool
    {
        return $threshold > $this->getPerceivedBrightness();
    }

    public function setType(string $type) : self
    {
        if (in_array($type, ['hsl', 'rgb'])) {
            $this->type = $type;
        }

        return $this;
    }

    public function toArray() : array
    {
        return $this->{$this->type}->toArray();
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    public function with(array $attrs) : ColorInterface
    {
        $props = array_keys($attrs);

        $withAlpha = isset($attrs['alpha']);
        $withHsl = ! empty(array_intersect(
            ['hue', 'saturation', 'lightness'],
            $props
        ));
        $withRgb = ! empty(array_intersect(
            ['red', 'green', 'blue'],
            $props
        ));

        if ($withHsl && $withRgb) {
            // @todo
            throw new \InvalidArgumentException;
        }

        if ($withHsl) {
            $color = new Color($this->hsl->with($attrs));
            $color->setType($this->getType());

            return $color;
        }

        if ($withRgb) {
            $color = new Color($this->rgb->with($attrs));
            $color->setType($this->getType());

            return $color;
        }

        if ($withAlpha) {
            return new Color($this->{$this->type}->with($attrs));
        }

        // @todo
        throw new \InvalidArgumentException;
    }
}
