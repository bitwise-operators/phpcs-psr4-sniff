<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use Override;

final readonly class NonAutoloadableClass implements InspectionResult
{
    public function __construct(
        private string $expectedClassName,
        private string $actualClassName,
    ) {
    }

    #[Override]
    public function getErrorMessage(
    ): string {
        return "Class name is not compliant with PSR-4 configuration. It should be `{$this->expectedClassName}` instead of `{$this->actualClassName}`.";
    }

    #[Override]
    public function isAutoloadable(
    ): bool {
        return false;
    }

    #[Override]
    public function isPsr4RelatedClass(
    ): bool {
        return true;
    }
}
