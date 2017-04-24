<?php

use PHPUnit\Framework\TestCase;
use function SSNepenthe\ColorUtils\modulo;
use function SSNepenthe\ColorUtils\restrict;
use function SSNepenthe\ColorUtils\value_is_between;
use function SSNepenthe\ColorUtils\array_contains_all_of;
use function SSNepenthe\ColorUtils\array_contains_one_of;

class HelpersTest extends TestCase
{
    /** @test */
    function it_correctly_determines_if_array_contains_all_given_keys()
    {
        $arr = ['one' => 1, 'two' => 2, 'three' => 3];

        // All.
        $this->assertTrue(array_contains_all_of($arr, ['one', 'two', 'three']));

        // One.
        $this->assertFalse(array_contains_all_of($arr, ['one', 'four', 'seven']));

        // None.
        $this->assertFalse(array_contains_all_of($arr, ['four', 'five', 'six']));
    }

    /** @test */
    function it_correctly_determines_if_array_contains_one_of_given_keys()
    {
        $arr = ['one' => 1, 'two' => 2, 'three' => 3];

        // All.
        $this->assertTrue(array_contains_one_of($arr, ['one', 'two', 'three']));

        // One.
        $this->assertTrue(array_contains_one_of($arr, ['one', 'four', 'seven']));

        // None.
        $this->assertFalse(array_contains_one_of($arr, ['four', 'five', 'six']));
    }

    /** @test */
    function it_correctly_performs_x_mod_y()
    {
        // In range.
        $this->assertSame(75.0, modulo(75, 100));

        // Bottom of or below range.
        $this->assertSame(0.0, modulo(0, 100));
        $this->assertSame(85.0, modulo(-15, 100));
        $this->assertSame(80.0, modulo(-320, 100));

        // Top of or above range.
        $this->assertSame(0.0, modulo(100, 100));
        $this->assertSame(15.0, modulo(115, 100));
        $this->assertSame(20.0, modulo(420, 100));
    }

    /** @test */
    function it_restricts_value_to_given_range()
    {
        // In range.
        $this->assertEquals(25, restrict(25, 0, 100));

        // Bottom of or below range.
        $this->assertEquals(0, restrict(0, 0, 100));
        $this->assertEquals(0, restrict(-5, 0, 100));

        // Top of or above range.
        $this->assertEquals(100, restrict(100, 0, 100));
        $this->assertEquals(100, restrict(105, 0, 100));
    }

    /** @test */
    function it_can_tell_if_value_is_in_range_inclusive()
    {
        $this->assertTrue(value_is_between(25, 0, 50));
        $this->assertFalse(value_is_between(25, 50, 100));

        $this->assertTrue(value_is_between(0, 0, 50));
        $this->assertTrue(value_is_between(50, 0, 50));
    }
}
