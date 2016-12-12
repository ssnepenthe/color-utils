<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\ColorInterface;

/**
 * Not exactly in-line with the SASS function. SASS uses the user supplied weight
 * along with the alpha value of each color to determine the actual weight each color
 * should be given.
 *
 * Since this package does not yet support alpha, it is assumed that the alpha value
 * is always 1 and therefore the user supplied weight is not adjusted.
 */
class Mix implements TransformerInterface
{
    protected $color;
    protected $weight;

    public function __construct(ColorInterface $color, int $weight = 50)
    {
        $this->color = $color;

        if (0 > $weight) {
            $weight = 0;
        }

        if (100 < $weight) {
            $weight = 100;
        }

        $this->weight = $weight;
    }

    public function transform(ColorInterface $color) : ColorInterface
    {
        $p = floatval($this->weight / 100.0);
        $w = $p * 2 - 1;
        $a = $this->color->getAlpha() - $color->getAlpha();

        $w1 = (($w * $a == -1 ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2.0;
        $w2 = 1 - $w1;

        $rgba = ['red' => 0, 'green' => 0, 'blue' => 0];

        foreach ($rgba as $key => $value) {
            $getter = 'get' . ucfirst($key);

            $rgba[$key] = intval(round(
                $this->color->{$getter}() * $w1 + $color->{$getter}() * $w2
            ));
        }

        if ($this->color->hasAlpha() || $color->hasAlpha()) {
            $rgba['alpha'] = $this->color->getAlpha() * $p + $color->getAlpha() * (1 - $p);
        }

        return $color->with($rgba);
    }
}
