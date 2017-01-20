<?php

namespace SSNepenthe\ColorUtils\Colors;

/**
 * Interface ColorInterface
 */
interface ColorInterface
{
    /**
     * @return array
     */
    public function toArray() : array;

    /**
     * @return Color
     */
    public function toColor() : Color;

    /**
     * @return string
     */
    public function toString() : string;

    /**
     * @param array $channels
     * @return ColorInterface
     */
    public function with(array $channels) : ColorInterface;
}
