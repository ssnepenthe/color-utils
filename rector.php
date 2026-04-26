<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/inc',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withDeadCodeLevel(53)
    ->withCodeQualityLevel(0)
    ->withPreparedSets(typeDeclarations: true)
    ->withSkip([
        AddArrayFunctionClosureParamTypeRector::class => [
            __DIR__ . '/src/Colors/Hsl.php',
            __DIR__ . '/src/Colors/Rgb.php',
        ],
    ]);
