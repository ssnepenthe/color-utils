<?php

namespace SSNepenthe\ColorUtils;

use InvalidArgumentException;

/**
 * @todo ConverterInterface
 */
class Color implements ColorInterface
{
    protected $hsl;
    protected $rgb;
    protected $type;

    public function __construct(
        ColorInterface $color,
        Converter $converter = null
    ) {
        $this->type = strtolower(
            (new \ReflectionClass($color))->getShortName()
        );

        $props = array_diff(['hsl', 'rgb'], [$this->type]);
        $prop = reset($props);

        $this->{$this->type} = $color;
        $this->converter = is_null($converter) ? new Converter : $converter;

        // I.e. hslToRgb.
        $converterMethod = sprintf('%sTo%s', $this->type, ucfirst($prop));
        $this->{$prop} = $this->converter->{$converterMethod}($this->{$this->type});
    }

    public function __toString() : string
    {
        return (string) $this->{$this->type};
    }

    public static function fromHsl(...$args) : ColorInterface
    {
        return new static(new Hsl(...$args));
    }

    public static function fromRgb(...$args) : ColorInterface
    {
        return new static(new Rgb(...$args));
    }

    public static function fromString(string $color) : ColorInterface
    {
        if ('hsl' === substr($color, 0, 3)) {
            return new static(Hsl::fromString($color));
        }

        return new static(Rgb::fromString($color));
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

    public function getHue() : float
    {
        return $this->hsl->getHue();
    }

    public function getLightness() : float
    {
        return $this->hsl->getLightness();
    }

    public function getName() : string
    {
        return $this->rgb->getName();
    }

    /**
     * @todo Should this method be on the Rgb class?
     */
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

    public function getSaturation() : float
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

    /**
     * To clone or not to clone?
     */
    public function toColor() : Color
    {
        return $this;
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    public function with(array $attrs) : ColorInterface
    {
        $props = array_keys($attrs);

        $withHsl = ! empty(array_intersect(
            ['hue', 'saturation', 'lightness'],
            $props
        ));
        $withRgb = ! empty(array_intersect(
            ['red', 'green', 'blue'],
            $props
        ));

        if ($withHsl && $withRgb) {
            throw new InvalidArgumentException(
                'You cannot modify HSL and RGB components in the same operation'
            );
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

        if (isset($attrs['alpha'])) {
            return new Color($this->{$this->type}->with($attrs));
        }

        throw new InvalidArgumentException('No valid components provided');
    }
}
