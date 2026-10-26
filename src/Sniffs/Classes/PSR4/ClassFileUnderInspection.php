<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use RuntimeException;

use function ltrim;
use function realpath;

final readonly class ClassFileUnderInspection
{
    private string $className;

    private string $fileName;

    public function __construct(
        string $fileName,
        string $className,
    ) {
        $realFileName = realpath($fileName);
        if ($realFileName === false) {
            throw new RuntimeException("Invalid file {$fileName}");
        }

        $this->fileName = $realFileName;
        $this->className = ltrim($className, '\\');
    }

    public function getClassName(
    ): string {
        return $this->className;
    }

    public function getFileName(
    ): string {
        return $this->fileName;
    }
}
