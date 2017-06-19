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

    /**
     * @param array $parsers
     */
    public function __construct(array $parsers)
    {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    /**
     * @param string $color
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
     * @param ParserInterface $parser
     * @return void
     */
    protected function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }
}
