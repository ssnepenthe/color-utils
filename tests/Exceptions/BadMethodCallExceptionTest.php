<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Exceptions\ExceptionInterface;
use SSNepenthe\ColorUtils\Exceptions\BadMethodCallException;

class BadMethodCallExceptionTest extends TestCase
{
    /** @test */
    function it_reflects_expected_hierarchy()
    {
        $e = new BadMethodCallException('test');

        $this->assertInstanceOf(ExceptionInterface::class, $e);
        $this->assertInstanceOf(\BadMethodCallException::class, $e);
    }
}
