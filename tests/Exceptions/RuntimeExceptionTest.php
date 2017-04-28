<?php

use PHPUnit\Framework\TestCase;
use SSNepenthe\ColorUtils\Exceptions\RuntimeException;
use SSNepenthe\ColorUtils\Exceptions\ExceptionInterface;

class RuntimeExceptionTest extends TestCase
{
    /** @test */
    function it_reflects_expected_hierarchy()
    {
        $e = new RuntimeException('test');

        $this->assertInstanceOf(ExceptionInterface::class, $e);
        $this->assertInstanceOf(\RuntimeException::class, $e);
    }
}
