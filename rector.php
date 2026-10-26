<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector;
use Rector\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_85)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withImportNames() // Force use statements for namespaces
    ->withPhpSets()
    ->withSets([
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION,
    ])
    ->withRules([
        DeclareStrictTypesRector::class,
    ])
    ->withConfiguredRule(
        AddOverrideAttributeToOverriddenMethodsRector::class,
        [
            'allow_override_empty_method' => true,
        ],
    )
    ->withSkip([ // remove unwanted rules included by sets
        AddMethodCallBasedStrictParamTypeRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        EmptyOnNullableObjectToInstanceOfRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        ScalarParamTypeByMethodCallTypeRector::class,
        SimplifyDeMorganBinaryRector::class,
        SimplifyEmptyCheckOnEmptyArrayRector::class,
        SortAttributeNamedArgsRector::class,
        SortCallLikeNamedArgsRector::class,
        ThrowWithPreviousExceptionRector::class,
    ])
;
