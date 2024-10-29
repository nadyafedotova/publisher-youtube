<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', nullable: true)]
    protected $refreshToken;

    #[ORM\Column(type: 'string', nullable: true)]
    protected $username;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $valid;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'refresh_tokens')]
    private UserInterface $user;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    #[ORM\PrePersist]
    final public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    final public function getId(): mixed
    {
        return $this->id;
    }

    final public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    final public function getUsername(): mixed
    {
        return $this->username;
    }

    final public function setUsername(mixed $username): void
    {
        $this->username = $username;
    }

    final public function getValid(): mixed
    {
        return $this->valid;
    }

    final public function setValid(mixed $valid): void
    {
        $this->valid = $valid;
    }

    final public function getUser(): UserInterface
    {
        return $this->user;
    }

    final public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    final public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function createForUserWithTtl(string $refreshToken, UserInterface $user, int $ttl): RefreshTokenInterface
    {
        /** @var RefreshToken $entity */
        $entity = parent::createForUserWithTtl($refreshToken, $user, $ttl);
        $entity->setUser($user);

        return $entity;
    }
}
