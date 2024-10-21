<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\IdResponse;
use App\Model\SingUpRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SingUpService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    final public function singUp(SingUpRequest $singUpRequest): IdResponse
    {
        if ($this->userRepository->existsByEmail($singUpRequest->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $user = new User();
        $user->setFirstName($singUpRequest->getFirstName());
        $user->setLastName($singUpRequest->getLastName());
        $user->setEmail($singUpRequest->getEmail());

        $user->setPassword($this->passwordHasher->hashPassword($user, $singUpRequest->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new IdResponse($user->getId());
    }


}
