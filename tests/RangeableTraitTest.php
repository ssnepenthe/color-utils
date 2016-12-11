<?php

use SSNepenthe\ColorUtils\RangeableTrait;

/**
 * Not sure I should be testing a trait in this way...
 *
 * For that matter I am not sure I should even be using a trait this way at all...
 */
class RangeableTraitTest extends PHPUnit_Framework_TestCase
{
    use RangeableTrait;

    public function test_it_doesnt_force_ints_if_they_are_in_range()
    {
        $this->assertEquals(25, $this->forceIntoRange(25, 0, 100));
    }

    public function test_it_can_force_ints_into_range()
    {
        $this->assertEquals(0, $this->forceIntoRange(-5, 0, 100));
        $this->assertEquals(100, $this->forceIntoRange(105, 0, 100));
    }

    public function test_it_can_tell_if_ints_are_out_of_range()
    {
        $this->assertFalse($this->isOutOfRange(50, 0, 100));
        $this->assertTrue($this->isOutOfRange(-10, 0, 100));
        $this->assertTrue($this->isOutOfRange(110, 0, 100));
    }

    public function test_it_doesnt_shift_ints_if_they_are_already_in_range()
    {
        $this->assertEquals(75, $this->shiftIntoRange(75, 0, 100));
    }

    public function test_it_can_shift_ints_into_range()
    {
        $this->assertEquals(15, $this->shiftIntoRange(115, 0, 100));
        $this->assertEquals(85, $this->shiftIntoRange(-15, 0, 100));
    }

    public function test_it_can_perform_multiple_shifts_on_an_int()
    {
        $this->assertEquals(20, $this->shiftIntoRange(420, 0, 100));
        $this->assertEquals(80, $this->shiftIntoRange(-320, 0, 100));
    }
}
