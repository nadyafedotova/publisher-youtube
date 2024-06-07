<?php

declare(strict_types=1);

namespace App\Model;

readonly class ErrorDebugDetails
{
    final public function __construct(
        private string $trace,
    ) {
    }

    final public function getTrace(): string
    {
        return $this->trace;
    }
}
