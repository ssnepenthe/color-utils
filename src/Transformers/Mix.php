<?php

namespace SSNepenthe\ColorUtils\Transformers;

use SSNepenthe\ColorUtils\Color;

class Mix implements TransformerInterface
{
    protected $color;
    protected $weight;

    public function __construct(Color $color, int $weight = 50)
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

    public function transform(Color $color) : Color
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
