<?php

namespace SSNepenthe\ColorUtils\Colors;

/**
 * Interface ColorInterface
 */
interface ColorInterface
{
    public function toArray() : array;

    public function toColor() : Color;

    public function toString() : string;

    public function with(array $channels) : ColorInterface;
}
