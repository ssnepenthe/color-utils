<?php

namespace SSNepenthe\ColorUtils\Colors;

use SSNepenthe\ColorUtils\Converters\HslToRgb;
use SSNepenthe\ColorUtils\Converters\RgbToHsl;
use function SSNepenthe\ColorUtils\contrast_ratio;
use SSNepenthe\ColorUtils\Exceptions\RuntimeException;
use SSNepenthe\ColorUtils\Converters\ConverterInterface;
use function SSNepenthe\ColorUtils\array_contains_one_of;
use SSNepenthe\ColorUtils\Exceptions\BadMethodCallException;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class Color
 */
class Color
{
    /**
     * @var array
     */
    protected $representations = [];

    /**
     * @param string $method
     * @param mixed $args
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if ('toColor' === $method) {
            // Don't proxy ->toColor() calls, just return this instance.
            return $this;
        }

        foreach ($this->representations as $representation) {
            if (method_exists($representation, $method)) {
                return $representation->{$method}(...$args);
            }
        }

        throw new BadMethodCallException(sprintf(
            'Method %s does not exist on any color representation',
            $method
        ));
    }

    /**
     * @param ColorInterface $color
     * @param string|null $base
     */
    public function __construct(ColorInterface $color, string $base = null)
    {
        $this->representations[] = $color;

        $converter = $this->makeConverter($color);

        $this->representations[] = $converter->convert($color);

        if (! is_null($base)) {
            usort($this->representations, function ($one, $two) use ($base) : int {
                if ($one instanceof $base) {
                    return -1;
                }

                if ($two instanceof $base) {
                    return 1;
                }

                return 0;
            });
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->representations[0]->__toString();
    }

    /**
     * @param Color $other
     * @return float
     */
    public function calculateBrightnessDifferenceWith(Color $other) : float
    {
        $brightness1 = $this->getRgb()->calculateBrightness();
        $brightness2 = $other->getRgb()->calculateBrightness();

        return abs($brightness1 - $brightness2);
    }

    /**
     * @param Color $other
     * @return int
     */
    public function calculateColorDifferenceWith(Color $other) : int
    {
        $rgb1 = $this->getRgb()->toArray();
        $rgb2 = $other->getRgb()->toArray();

        return abs($rgb1['red'] - $rgb2['red'])
            + abs($rgb1['green'] - $rgb2['green'])
            + abs($rgb1['blue'] - $rgb2['blue']);
    }

    /**
     * @param Color $other
     * @return float
     */
    public function calculateContrastRatioWith(Color $other) : float
    {
        $luminances = [
            $this->getRgb()->calculateRelativeLuminance(),
            $other->getRgb()->calculateRelativeLuminance(),
        ];

        return round((max($luminances) + 0.05) / (min($luminances) + 0.05), 5);
    }

    /**
     * @param string $class
     * @return ColorInterface
     * @throws RuntimeException
     */
    public function getRepresentation(string $class) : ColorInterface
    {
        foreach ($this->representations as $representation) {
            if ($representation instanceof $class) {
                return $representation;
            }
        }

        throw new RuntimeException("No instance of {$class} found");
    }

    /**
     * @return Hsl
     */
    public function getHsl() : Hsl
    {
        return $this->getRepresentation(Hsl::class);
    }

    /**
     * @return Rgb
     */
    public function getRgb() : Rgb
    {
        return $this->getRepresentation(Rgb::class);
    }

    /**
     * @param array $channels
     * @return Color
     * @throws InvalidArgumentException
     */
    public function with(array $channels) : Color
    {
        $withHsl = array_contains_one_of(
            $channels,
            ['hue', 'saturation', 'lightness']
        );
        $withRgb = array_contains_one_of($channels, ['red', 'green', 'blue']);

        if ($withHsl && $withRgb) {
            throw new InvalidArgumentException(
                'You cannot modify HSL and RGB components in the same operation'
            );
        }

        if ($withHsl) {
            return new static(
                $this->getHsl()->with($channels),
                get_class($this->representations[0])
            );
        }

        if ($withRgb) {
            return new static(
                $this->getRgb()->with($channels),
                get_class($this->representations[0])
            );
        }

        // Only modifying alpha.
        if (isset($channels['alpha'])) {
            return new static($this->representations[0]->with($channels));
        }

        throw new InvalidArgumentException('No valid components provided');
    }

    /**
     * @param ColorInterface $color
     * @return ConverterInterface
     * @throws InvalidArgumentException
     */
    protected function makeConverter(ColorInterface $color) : ConverterInterface
    {
        switch (get_class($color)) {
            case 'SSNepenthe\\ColorUtils\\Colors\\Rgb':
            case 'SSNepenthe\\ColorUtils\\Colors\\Rgba':
                return new RgbToHsl;
            case 'SSNepenthe\\ColorUtils\\Colors\\Hsl':
            case 'SSNepenthe\\ColorUtils\\Colors\\Hsla':
                return new HslToRgb;
            default:
                // Should never hit this.
                throw new InvalidArgumentException(
                    'Unrecognized ColorInterface type'
                );
        }
    }
}
