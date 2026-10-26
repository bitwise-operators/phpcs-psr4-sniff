<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes;

use Override;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use Suin\Sniffs\Classes\PSR4\AutoloadabilityInspectors;
use Suin\Sniffs\Classes\PSR4\AutoloadabilityInspectorsFactory;
use Suin\Sniffs\Classes\PSR4\ClassFileUnderInspection;
use Suin\Sniffs\Classes\PSR4\InspectionResult;

use function assert;
use function is_string;

use const T_CLASS;
use const T_INTERFACE;
use const T_STRING;
use const T_TRAIT;

final class PSR4Sniff implements Sniff
{
    public const string CODE_INCORRECT_CLASS_NAME = 'IncorrectClassName';

    private const int INITIALIZATION_FAILURE = -1;
    private const int INITIALIZED = 1;
    private const int UNINITIALIZED = 0;

    private AutoloadabilityInspectors $autoloadabilityInspectors;

    /**
     * File path of "composer.json".
     * This must be relative path to "--basepath" option of phpcs command.
     */
    public string $composerJsonPath = 'composer.json';

    private int $initialization = self::UNINITIALIZED;

    private function addError(
        File $phpcsFile,
        InspectionResult $result,
        int $typePointer,
    ): void {
        $phpcsFile->addError(
            $result->getErrorMessage(),
            TokenHelper::findNext($phpcsFile, T_STRING, $typePointer + 1),
            self::CODE_INCORRECT_CLASS_NAME,
        );
    }

    private function initializeThisSniffIfNotYet(
        Config $config,
    ): void {
        if ($this->initialization === self::UNINITIALIZED) {
            $this->initialization = self::INITIALIZATION_FAILURE;
            $basepath = $config->getSettings()['basepath'];
            assert($basepath === null || is_string($basepath));
            $this->autoloadabilityInspectors = AutoloadabilityInspectorsFactory::create(
                $basepath,
                $this->composerJsonPath,
            );
            $this->initialization = self::INITIALIZED;
        }
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function process(
        File $phpcsFile,
        $typePointer,
    ): void {
        $this->initializeThisSniffIfNotYet($phpcsFile->config);

        if ($this->initialization !== self::INITIALIZATION_FAILURE) {
            $result = $this->autoloadabilityInspectors
                ->inspect(
                    new ClassFileUnderInspection(
                        $phpcsFile->getFilename(),
                        ClassHelper::getFullyQualifiedName($phpcsFile, $typePointer),
                    ),
                )
            ;

            if (
                $result->isPsr4RelatedClass()
                && !$result->isAutoloadable()
            ) {
                $this->addError($phpcsFile, $result, $typePointer);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function register(
    ): array {
        return [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        ];
    }
}
