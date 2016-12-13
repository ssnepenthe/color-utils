<?php

namespace SSNepenthe\ColorUtils;

interface ColorInterface
{
    public static function fromString(string $color) : ColorInterface;
    public function getAlpha() : float;
    public function hasAlpha() : bool;
    public function toArray() : array;
    public function toColor(): Color;
    public function toString() : string;
    public function with(array $attributes) : ColorInterface;
}
