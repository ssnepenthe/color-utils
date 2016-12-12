<?php

namespace SSNepenthe\ColorUtils;

trait RangeableTrait
{
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

    protected function isOutOfRange($value, $min, $max) : bool
    {
        if ($min > $value || $max < $value) {
            return true;
        }

        return false;
    }

    protected function shiftIntoRange($value, $min, $max)
    {
        while ($this->isOutOfRange($value, $min, $max)) {
            if ($min > $value) {
                $value += $max;
            }

            if ($max < $value) {
                $value -= $max;
            }
        }

        return $value;
    }
}
