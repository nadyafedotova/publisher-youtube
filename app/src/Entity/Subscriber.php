<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubscriberRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    #[ORM\PrePersist]
    final public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getEmail(): string
    {
        return $this->email;
    }

    final public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    final public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
