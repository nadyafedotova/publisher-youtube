<?php

namespace App\Model;

readonly class IdResponse
{
    public function __construct(
        private int $id
    ) {
    }

    final public function getId(): int
    {
        return $this->id;
    }
}
