<?php

declare(strict_types=1);

namespace App\Model;

use App\Attribute\RequestBody;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubscriberRequest
{
    #[Email]
    #[NotBlank]
    private string $email;

    #[IsTrue]
    #[NotBlank]
    private bool $agreed;

    final public function getEmail(): string
    {
        return $this->email;
    }

    final public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    final public function isAgreed(): bool
    {
        return $this->agreed;
    }

    final public function setAgreed(bool $agreed): void
    {
        $this->agreed = $agreed;
    }
}
