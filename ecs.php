<?php

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withSets([
        SetList::PSR_12,
        SetList::CLEAN_CODE,
    ]);
