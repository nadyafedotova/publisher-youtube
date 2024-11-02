<?php

declare(strict_types=1);

namespace App\Attribute;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class RequestFile
{
    public function __construct(
        private string           $field,
        private Constraint|array $constraints = [],
    ) {
    }

    final public function getField(): string
    {
        return $this->field;
    }

    final public function getConstraints(): Constraint|array
    {
        return $this->constraints;
    }
}
