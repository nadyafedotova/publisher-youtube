<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use JsonException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws JsonException
     */
    final public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    /**
     * @throws JsonException
     */
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

    /**
     * @throws JsonException
     */
    final public function getUser(string $key, mixed $value): User
    {
        $user = $this->userRepository->findOneBy([$key => $value]);

        if (null === $user) {
            $e = new UserNotFoundException('User with id '.json_encode($value, JSON_THROW_ON_ERROR).' not found.');
            $e->setUserIdentifier(json_encode($value, JSON_THROW_ON_ERROR));

            throw $e;
        }

        return $user;
    }
}
