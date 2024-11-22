<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SingUpRequest;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SingUpService
{
    public function __construct(
        private UserPasswordHasherInterface  $passwordHasher,
        private UserRepository               $userRepository,
        private AuthenticationSuccessHandler $authenticationSuccessHandler,
    ) {
    }

    final public function singUp(SingUpRequest $singUpRequest): Response
    {
        if ($this->userRepository->existsByEmail($singUpRequest->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $user = (new User())
            ->setRoles(['ROLE_USER'])
            ->setFirstName($singUpRequest->getFirstName())
            ->setLastName($singUpRequest->getLastName())
            ->setEmail($singUpRequest->getEmail());
        $user->setPassword($this->passwordHasher->hashPassword($user, $singUpRequest->getPassword()));
        $this->userRepository->saveAndCommit($user);

        return $this->authenticationSuccessHandler->handleAuthenticationSuccess($user);
    }
}
