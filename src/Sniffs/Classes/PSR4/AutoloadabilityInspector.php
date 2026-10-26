<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use RuntimeException;

use function assert;
use function basename;
use function mb_strlen;
use function mb_substr;
use function pathinfo;
use function realpath;
use function rtrim;
use function str_ends_with;
use function str_replace;
use function str_starts_with;

use const PATHINFO_DIRNAME;
use const PATHINFO_FILENAME;

final readonly class AutoloadabilityInspector
{
    private string $baseDirectory;

    private string $namespacePrefix;

    public function __construct(
        string $baseDirectory,
        string $namespacePrefix,
    ) {
        $realBaseDirectory = realpath($baseDirectory);
        if ($realBaseDirectory === false) {
            throw new RuntimeException("Invalid path {$baseDirectory}");
        }
        $this->baseDirectory = rtrim($realBaseDirectory, '/') . '/';
        $this->namespacePrefix = rtrim($namespacePrefix, '\\') . '\\';
    }

    private function classFileIsUnderBaseDirectory(
        ClassFileUnderInspection $classFile,
    ): bool {
        return str_starts_with($classFile->getFileName(), $this->baseDirectory);
    }

    private function guessExpectedClassName(
        ClassFileUnderInspection $classFile,
    ): string {
        $relativeFileName = $this->guessRelativeFileName($classFile);
        $relativeClassName = $this->guessRelativeClassName($relativeFileName);
        return $this->guessFullyQualifiedClassName($relativeClassName);
    }

    private function guessFullyQualifiedClassName(
        string $relativeClassName,
    ): string {
        assert(str_ends_with($this->namespacePrefix, '\\'));
        return $this->namespacePrefix . $relativeClassName;
    }

    private function guessRelativeClassName(
        string $relativeFileName,
    ): string {
        $basename = basename($relativeFileName);
        $filename = pathinfo($relativeFileName, PATHINFO_FILENAME);
        $dirname = $basename === $relativeFileName
            ? ''
            : pathinfo($relativeFileName, PATHINFO_DIRNAME) . '/';
        return str_replace('/', '\\', $dirname) . $filename;
    }

    private function guessRelativeFileName(
        ClassFileUnderInspection $classFile,
    ): string {
        assert(str_ends_with($this->baseDirectory, '/'));
        assert($this->classFileIsUnderBaseDirectory($classFile));
        return mb_substr(
            $classFile->getFileName(),
            mb_strlen($this->baseDirectory),
        );
    }

    public function inspect(
        ClassFileUnderInspection $classFile,
    ): InspectionResult {
        return $this->classFileIsUnderBaseDirectory($classFile)
            ? $this->inspectAutoloadability($classFile)
            : new PSR4UnrelatedClass();
    }

    private function inspectAutoloadability(
        ClassFileUnderInspection $classFile,
    ): InspectionResult {
        $expectedClassName = $this->guessExpectedClassName($classFile);
        $actualClassName = $classFile->getClassName();
        return $expectedClassName === $actualClassName
            ? new AutoloadableClass()
            : new NonAutoloadableClass($expectedClassName, $actualClassName);
    }
}
