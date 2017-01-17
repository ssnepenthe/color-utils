<?php

namespace SSNepenthe\ColorUtils;

/**
 * Ruby modulo implementation - PHP modulo handles negative numbers differently.
 *
 * @link http://ruby-doc.org/core-2.4.0/Numeric.html#method-i-modulo
 */
function modulo($value1, $value2)
{
    return $value1 - $value2 * floor($value1 / $value2);
}

function restrict($value, $min, $max)
{
    return min(max($value, $min), $max);
}

function array_contains_one_of(array $array, array $keys)
{
    return ! empty(array_intersect($keys, array_keys($array)));
}
