<?php

declare(strict_types=1);

namespace App\Model\Author;

readonly class UploadCoverResponse
{
    public function __construct(
        private string $link,
    ) {
    }

    final public function getLink(): string
    {
        return $this->link;
    }
}
