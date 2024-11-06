<?php

namespace App\Tests\src\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use App\Security\JwtUserProvider;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;

class JwtUserProviderTest extends AbstractTestCase
{
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
    }

    final public function testSupportsClass(): void
    {
        $user = (new User())->setEmail('john.doe@example.com');
        $provider = new JwtUserProvider($this->userRepository);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'john.doe@example.com'])
            ->willReturn($user);

        $this->assertEquals($user, $provider->loadUserByIdentifier('john.doe@example.com'));
    }

    final public function testLoadUserByIdentifierNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'john.doe@example.com'])
            ->willReturn(null);

        (new JwtUserProvider($this->userRepository))->loadUserByIdentifier('john.doe@example.com');
    }

    final public function testLoadUserByIdentifierAndPayload(): void
    {
        $user = (new User())->setEmail('john.doe@example.com');
        $provider = new JwtUserProvider($this->userRepository);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($user);

        $this->assertEquals($user, $provider->loadUserByIdentifierAndPayload('john.doe@example.com', ['id' => 1]));

    }

    final public function testLoadUserByIdentifierAndPayloadNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(null);

        (new JwtUserProvider($this->userRepository))->loadUserByIdentifierAndPayload('john.doe@example.com', ['id' => 1]);
    }
}
