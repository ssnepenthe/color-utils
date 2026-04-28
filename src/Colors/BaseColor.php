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

    public function __toString() : string
    {
        return sprintf(
            '%s(%s)',
            $this->getStringPrefix(),
            implode(', ', $this->toStringifiedArray())
        );
    }

    public function getAlpha() : float
    {
        return round($this->alpha, 5);
    }

    public function hasAlpha() : bool
    {
        return 1.0 != $this->getAlpha();
    }

    public function toColor() : Color
    {
        return new Color($this);
    }

    public function toString() : string
    {
        return $this->__toString();
    }

    abstract public function toArray() : array;

    abstract public function with(array $channels) : ColorInterface;

    abstract protected function getStringPrefix() : string;

    abstract protected function toStringifiedArray() : array;
}
