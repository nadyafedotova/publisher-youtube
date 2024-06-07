<?php

declare(strict_types=1);

namespace App\Model;

class ErrorValidationDetails
{
    /**
     * @var ErrorValidationDetailsItem[]
     */
    private array $violations = [];

    final public function addViolations(string $field, string $message): void
    {
        $this->violations[] = new ErrorValidationDetailsItem($field, $message);
    }

    /**
     * @return ErrorValidationDetailsItem[]
     */
    final public function getViolations(): array
    {
        return $this->violations;
    }
}
