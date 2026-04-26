<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector;
use Rector\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/inc',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(codeQuality: true, deadCode: true, typeDeclarations: true)
    ->withSkip([
        AddArrayFunctionClosureParamTypeRector::class => [
            __DIR__ . '/src/Colors/Hsl.php',
            __DIR__ . '/src/Colors/Rgb.php',
        ],
        SafeDeclareStrictTypesRector::class,
        UseIdenticalOverEqualWithSameTypeRector::class => [
            __DIR__ . '/src/Colors/BaseColor.php',
        ],
    ]);
