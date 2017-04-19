<?php

namespace SSNepenthe\ColorUtils;

/**
 * @param array $array
 * @param array $keys
 * @return bool
 */
function array_contains_all_of(array $array, array $keys) : bool
{
    return $keys === array_intersect($keys, array_keys($array));
}

/**
 * @param array $array
 * @param array $keys
 * @return bool
 */
function array_contains_one_of(array $array, array $keys) : bool
{
    return ! empty(array_intersect($keys, array_keys($array)));
}

/**
 * @param mixed $value1
 * @param mixed $value2
 * @return float
 */
function modulo($value1, $value2) : float
{
    // PHP % handles negative number differently than Ruby so here is a Ruby implementation.
    // @link http://ruby-doc.org/core-2.4.0/Numeric.html#method-i-modulo
    return $value1 - $value2 * floor($value1 / $value2);
}

/**
 * @param mixed $value
 * @param mixed $min
 * @param mixed $max
 * @return mixed
 */
function restrict($value, $min, $max)
{
    return min(max($value, $min), $max);
}

/**
 * @param mixed $value
 * @param mixed $min
 * @param mixed $max
 * @return bool
 */
function value_is_between($value, $min, $max) : bool
{
    return $min <= $value && $value <= $max;
}

/**
 * @param mixed ...$args
 * @return bool
 */
function _color_args_probably_contain_extra_arg(...$args) : bool
{
    $count = count($args);

    if (2 === $count || 5 === $count) {
        return true;
    }

    if (4 === $count) {
        // 4th arg is likely alpha if numeric and between 0 - 1, otherwise extra.
        return is_numeric($args[3]) && 1 <= $args[3];
    }

    return false;
}
