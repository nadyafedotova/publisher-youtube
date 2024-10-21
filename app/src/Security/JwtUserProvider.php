<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    final public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    final public function loadUserByIdentifierAndPayload(string $identifier, array $payload): UserInterface
    {
        return $this->getUser('id', $payload['id']);
    }

    final public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    final public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, UserInterface::class);
    }

    final public function getUser(string $key, mixed $value): User
    {
        $user = $this->userRepository->findOneBy([$key => $value]);

        if (null === $user) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $value));
            $e->setUserIdentifier($value);
            throw $e;
        }

        return $user;
    }
}
