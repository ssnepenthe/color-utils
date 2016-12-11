<?php

use SSNepenthe\ColorUtils\Color;

class TransformerTestCase extends PHPUnit_Framework_TestCase
{
    protected function runTransformerTests(
        Color $color,
        array $tests,
        string $resultType = null
    ) {
        if (is_null($resultType)) {
            $resultType = $color->getType();
        }

        $typeMethod = 'get' . ucfirst($resultType);

        foreach ($tests as $test) {
            $formatMethod = is_array($test['result']) ? 'toArray' : 'toString';

            $this->assertEquals(
                $test['result'],
                $test['transformer']->transform($color)->{$typeMethod}()->{$formatMethod}()
            );
        }
    }
}
