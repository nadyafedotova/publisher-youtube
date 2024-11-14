<?php

declare(strict_types=1);

namespace App\Model\Author;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private \DateTimeInterface $dateTime;
    final public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    final public function setDateTime(?DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
