<?php

declare(strict_types=1);

namespace App\Model;

readonly class ErrorResponse
{
    public function __construct(
        private string $message,
        private mixed $details = null,
    ) {
    }

    final public function getMessage(): string
    {
        return $this->message;
    }

    final public function getDetails(): mixed
    {
        return $this->details;
    }
}
