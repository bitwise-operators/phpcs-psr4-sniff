<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use LogicException;
use Override;

final readonly class PSR4UnrelatedClass implements InspectionResult
{
    #[Override]
    public function getErrorMessage(
    ): never {
        throw new LogicException();
    }

    #[Override]
    public function isAutoloadable(
    ): bool {
        return false;
    }

    #[Override]
    public function isPsr4RelatedClass(
    ): bool {
        return false;
    }
}
