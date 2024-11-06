<?php

namespace App\Tests\src\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RoleService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;

class RoleServiceTest extends AbstractTestCase
{
    private UserRepository $userRepository;
    private User $user;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with(1)
            ->willReturn($this->user);

        $this->userRepository->expects($this->once())
            ->method('commit');
    }

    private function createService(): RoleService
    {
        return new RoleService($this->userRepository);
    }
    final public function testGrantAuthor(): void
    {
        $this->createService()->grantAuthor(1);
        $this->assertEquals(['ROLE_AUTHOR'], $this->user->getRoles());
    }

    final public function testGrantAdmin(): void
    {
        $this->createService()->grantAdmin(1);
        $this->assertEquals(['ROLE_ADMIN'], $this->user->getRoles());
    }
}
