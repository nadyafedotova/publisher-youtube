<?php

declare(strict_types=1);

namespace App\Model;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Schema;

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

    #[OA\Property(
        type: "object",
        oneOf: [
            new Schema(ref: new Model(type: ErrorDebugDetails::class)),
            new Schema(ref: new Model(type: ErrorValidationDetails::class))
        ]
    )]
    final public function getDetails(): mixed
    {
        return $this->details;
    }
}
