<?php

declare(strict_types=1);

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private \DateTimeInterface $dateTime;
    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}
