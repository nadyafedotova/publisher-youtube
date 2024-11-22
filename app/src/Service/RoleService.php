<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

readonly class RoleService
{
    public function __construct(
        private UserRepository         $userRepository,
    ) {
    }

    final public function grantAdmin(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_ADMIN');
    }

    final public function grantAuthor(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_AUTHOR');
    }

    private function grantRole(int $userId, string $role): void
    {
        $user = $this->userRepository->getUser($userId);
        $user->setRoles([$role]);

        $this->userRepository->saveAndCommit($user);
    }
}
