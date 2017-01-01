<?php

use function SSNepenthe\ColorUtils\restrict;
use function SSNepenthe\ColorUtils\modulo;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    public function test_it_restricts_value_to_given_range()
    {
        $this->assertEquals(25, restrict(25, 0, 100));
        $this->assertEquals(0, restrict(-5, 0, 100));
        $this->assertEquals(100, restrict(105, 0, 100));
    }

    public function test_it_correctly_performs_x_mod_y()
    {
        $this->assertEquals(75, modulo(75, 100));
        $this->assertEquals(15, modulo(115, 100));
        $this->assertEquals(85, modulo(-15, 100));
        $this->assertEquals(20, modulo(420, 100));
        $this->assertEquals(80, modulo(-320, 100));
    }
}
