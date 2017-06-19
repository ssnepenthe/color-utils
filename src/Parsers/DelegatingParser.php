<?php

namespace SSNepenthe\ColorUtils\Parsers;

use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

/**
 * Class DelegatingParser
 */
class DelegatingParser implements ParserInterface
{
    /**
     * @var ParserResolverInterface
     */
    protected $resolver;

    /**
     * @param ParserResolverInterface $resolver
     */
    public function __construct(ParserResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $color
     * @return array
     * @throws InvalidArgumentException
     */
    public function parse(string $color) : array
    {
        if (false === $parser = $this->resolver->resolve($color)) {
            throw new InvalidArgumentException(sprintf(
                'String "%s" not supported in %s',
                $color,
                __METHOD__
            ));
        }

        return $parser->parse($color);
    }

    /**
     * @param string $color
     * @return bool
     */
    public function supports(string $color) : bool
    {
        return false !== $this->resolver->resolve($color);
    }
}
