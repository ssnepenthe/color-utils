<?php

namespace SSNepenthe\ColorUtils;

/**
 * Ruby modulo implementation - PHP modulo handles negative numbers differently.
 *
 * @link http://ruby-doc.org/core-2.4.0/Numeric.html#method-i-modulo
 */
function modulo($x, $y) {
    return $x - $y * floor($x / $y);
}

function restrict($value, $min, $max) {
    return min(max($value, $min), $max);
}
