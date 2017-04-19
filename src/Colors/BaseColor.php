<?php

namespace SSNepenthe\ColorUtils\Colors;

/**
 * Class BaseColor
 */
abstract class BaseColor implements ColorInterface
{
    /**
     * @var float
     */
    protected $alpha = 1.0;

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            '%s(%s)',
            $this->getStringPrefix(),
            implode(', ', $this->toStringifiedArray())
        );
    }

    /**
     * @return float
     */
    public function getAlpha() : float
    {
        return round($this->alpha, 5);
    }

    /**
     * @return bool
     */
    public function hasAlpha() : bool
    {
        return 1.0 != $this->getAlpha();
    }

    /**
     * @return Color
     */
    public function toColor() : Color
    {
        return new Color($this);
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        return $this->__toString();
    }

    /**
     * @return array
     */
    abstract public function toArray() : array;

    /**
     * @param array $channels
     * @return ColorInterface
     */
    abstract public function with(array $channels) : ColorInterface;

    /**
     * @return string
     */
    abstract protected function getStringPrefix() : string;

    /**
     * @return array
     */
    abstract protected function toStringifiedArray() : array;
}
