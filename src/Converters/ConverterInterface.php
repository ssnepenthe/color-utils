<?php

namespace SSNepenthe\ColorUtils\Converters;

use SSNepenthe\ColorUtils\Colors\ColorInterface;

/**
 * Interface ConverterInterface
 */
interface ConverterInterface
{
    /**
     * @param ColorInterface $color
     * @return ColorInterface
     */
    public function convert(ColorInterface $color) : ColorInterface;
}
