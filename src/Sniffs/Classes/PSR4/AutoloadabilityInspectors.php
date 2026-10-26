<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use function array_values;

final class AutoloadabilityInspectors
{
    /** @var list<AutoloadabilityInspector> */
    private array $inspectors = [];

    public function __construct(
        AutoloadabilityInspector ...$inspectors,
    ) {
        $this->inspectors = array_values($inspectors);
    }

    public function inspect(
        ClassFileUnderInspection $classFile,
    ): InspectionResult {
        $result = null;

        foreach ($this->inspectors as $inspector) {
            $inspectionResult = $inspector->inspect($classFile);

            if ($inspectionResult->isPsr4RelatedClass()) {
                $result = $inspectionResult;
                break;
            }
        }

        return $result
            ?? new PSR4UnrelatedClass();
    }
}
