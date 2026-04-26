<?php

namespace SSNepenthe\ColorUtils\Converters;

use SSNepenthe\ColorUtils\Colors\ColorInterface;

/**
 * Interface ConverterInterface
 */
interface ConverterInterface
{
    /**
     * @return ColorInterface
     */
    public function convert(ColorInterface $color) : ColorInterface;
}
