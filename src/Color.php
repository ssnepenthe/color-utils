<?php

namespace SSNepenthe\ColorUtils;

use InvalidArgumentException;

class Color implements ColorInterface
{
    protected $hsl;
    protected $rgb;
    protected $type;

    public function __construct(ColorInterface $color) {
        $this->type = strtolower(
            (new \ReflectionClass($color))->getShortName()
        );

        $props = array_diff(['hsl', 'rgb'], [$this->type]);
        $prop = reset($props);

        $this->{$this->type} = $color;

        // I.e. toRgb.
        $converterMethod = 'to' . ucfirst($prop);
        $this->{$prop} = $this->{$this->type}->{$converterMethod}();
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

    public function calculatePerceivedBrightness() : float
    {
        return $this->getRgb()->calculatePerceivedBrightness();
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

    public function isDark($threshold = 50.0) : bool
    {
        return $this->getHsl()->isDark($threshold);
    }

    public function isLight($threshold = 50.0) : bool
    {
        return $this->getHsl()->isLight($threshold);
    }

    public function looksDark($threshold = 50.0) : bool
    {
        return $this->getRgb()->looksDark($threshold);
    }

    public function looksLight($threshold = 50.0) : bool
    {
        return $this->getRgb()->looksLight($threshold);
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

    public function toColor() : Color
    {
        return $this;
    }

    public function toHsl() : Hsl
    {
        return $this->hsl;
    }

    public function toRgb() : Rgb
    {
        return $this->rgb;
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
