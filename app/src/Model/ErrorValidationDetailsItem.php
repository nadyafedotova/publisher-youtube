<?php

declare(strict_types=1);

namespace App\Model;

readonly class ErrorValidationDetailsItem
{
    public function __construct(
        private string $field,
        private string $message,
    ) {
    }

    final public function getField(): string
    {
        return $this->field;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }
}
