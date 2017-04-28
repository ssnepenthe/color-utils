<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Exceptions\ExceptionInterface;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException;

class InvalidArgumentExceptionTest extends TestCase
{
    /** @test */
    function it_reflects_expected_hierarchy()
    {
        $e = new InvalidArgumentException('test');

        $this->assertInstanceOf(ExceptionInterface::class, $e);
        $this->assertInstanceOf(\InvalidArgumentException::class, $e);
    }
}
