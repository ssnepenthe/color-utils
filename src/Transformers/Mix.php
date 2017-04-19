<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Colors\Color;
use function SSNepenthe\ColorUtils\restrict;

/**
 * Class Mix
 */
class Mix implements TransformerInterface
{
    /**
     * @var Color
     */
    protected $color;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @param Color $color
     * @param int $weight
     */
    public function __construct(Color $color, int $weight = 50)
    {
        $this->color = $color;
        $this->weight = restrict($weight, 0, 100);
    }

    /**
     * @param Color $color
     * @return Color
     */
    public function transform(Color $color) : Color
    {
        $percentage = $this->weight / 100;
        $scaledWeight = $percentage * 2 - 1;
        $alphaDiff = $this->color->getAlpha() - $color->getAlpha();

        $weight1 = (($scaledWeight * $alphaDiff == -1
            ? $scaledWeight
            : ($scaledWeight + $alphaDiff) / (1 + $scaledWeight * $alphaDiff)
        ) + 1) / 2;
        $weight2 = 1 - $weight1;

        $rgba = [];

        foreach (['red', 'green', 'blue'] as $key) {
            $getter = 'get' . ucfirst($key);

            $rgba[$key] = $this->color->{$getter}() * $weight1 + $color->{$getter}() * $weight2;
        }

        if ($this->color->hasAlpha() || $color->hasAlpha()) {
            $rgba['alpha'] = $this->color->getAlpha() * $percentage + $color->getAlpha() * (1 - $percentage);
        }

        return $color->with($rgba);
    }
}
