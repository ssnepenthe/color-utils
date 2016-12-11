<?php

namespace SSNepenthe\ColorUtils;

interface ColorInterface
{
    public function toArray() : array;
    public function toString() : string;
    public function with(array $attributes) : ColorInterface;
}
