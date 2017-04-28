<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Exceptions\LogicException;
use SSNepenthe\ColorUtils\Exceptions\ExceptionInterface;

class LogicExceptionTest extends TestCase
{
    /** @test */
    function it_reflects_expected_hierarchy()
    {
        $e = new LogicException('test');

        $this->assertInstanceOf(ExceptionInterface::class, $e);
        $this->assertInstanceOf(\LogicException::class, $e);
    }
}
