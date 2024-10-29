<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;
    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'simple_array')]
    private array $roles = [];

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(int $id): void
    {
        $this->id = $id;
    }

    final public function getFirstName(): string
    {
        return $this->firstName;
    }

    final public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    final public function getLastName(): string
    {
        return $this->lastName;
    }

    final public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    final public function getEmail(): string
    {
        return $this->email;
    }

    final public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    final public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    final public function getRoles(): array
    {
        return $this->roles;
    }

    final public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    final public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    final public function eraseCredentials(): void
    {
    }

    /**
     * @inheritDoc
     */
    final public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
