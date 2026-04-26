<?php

namespace SSNepenthe\ColorUtils\Parsers;

/**
 * Class ParserResolver
 */
class ParserResolver implements ParserResolverInterface
{
    /**
     * @var array
     */
    protected $parsers = [];

    public function __construct(array $parsers)
    {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    /**
     * @return ParserInterface|false
     */
    public function resolve(string $color)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($color)) {
                return $parser;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    protected function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }
}
