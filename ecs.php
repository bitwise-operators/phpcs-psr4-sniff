<?php

declare(strict_types=1);

use Suin\Sniffs\Classes\PSR4Sniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff;
use PhpCsFixer\Fixer\Casing\IntegerLiteralCaseFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentSpacingFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAfterConstructFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\StringNotation\SimpleToComplexStringVariableFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\TypeDeclarationSpacesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;
    
return ECSConfig::configure()
    ->withParallel()
    ->withSpacing(lineEnding: "\n")
    ->withPaths([
        __DIR__ . '/src',
        // __DIR__ . '/tests',
    ])
    ->withSets([
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ])
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withRules([
        ArrayIndentationFixer::class,
        IntegerLiteralCaseFixer::class,
        MethodArgumentSpaceFixer::class,
        MethodChainingIndentationFixer::class,
        NoShortBoolCastFixer::class,
        NoSpacesAroundOffsetFixer::class,
        OperatorLinebreakFixer::class,
        PSR4Sniff::class,
        SimpleToComplexStringVariableFixer::class,
        SingleLineCommentSpacingFixer::class,
        SingleSpaceAfterConstructFixer::class,
        TypeDeclarationSpacesFixer::class,
    ])
    ->withConfiguredRule(
        ClassDefinitionFixer::class,
        [
            'inline_constructor_arguments' => false,
            'multi_line_extends_each_single_line' => true,
        ],
    )
    ->withConfiguredRule(
        ForbiddenFunctionsSniff::class,
        [
            'forbiddenFunctions' => [
                'create_function' => null,
                'dd' => null,
                'delete' => 'unset',
                'die' => null,
                'dump' => null,
                'eval' => null,
                'print' => 'echo',
                'sizeof' => 'count',
                'var_dump' => null,
            ],
        ],
    )
    ->withConfiguredRule(
        FunctionSpacingSniff::class,
        [
            'spacing' => 1,
            'spacingAfterLast' => 0,
            'spacingBeforeFirst' => 0,
        ],
    )
    ->withConfiguredRule(
        LineLengthSniff::class,
        [
            'absoluteLineLimit' => 200,
            'lineLimit' => 160,
        ],
    )
    ->withConfiguredRule(
        MultilineWhitespaceBeforeSemicolonsFixer::class,
        [
            'strategy' => MultilineWhitespaceBeforeSemicolonsFixer::STRATEGY_NEW_LINE_FOR_CHAINED_CALLS,
        ],
    )
    ->withConfiguredRule(   
        TrailingCommaInMultilineFixer::class,
        [
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
    )
    ->withConfiguredRule(
        TypesSpacesFixer::class,
        [
            'space' => 'single',
        ],
    )
;
