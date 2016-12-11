<?php

namespace SSNepenthe\ColorUtils;

trait RangeableTrait {
    protected function forceIntoRange($value, $min, $max)
    {
        if ($min > $value) {
            return $min;
        }

        if ($max < $value) {
            return $max;
        }

        return $value;
    }
}
