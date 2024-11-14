<?php

namespace App\Tests\src\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\JwtUserProvider;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class JwtUserProviderTest extends AbstractTestCase
{
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
    }

    final public function testSupportsClass(): void
    {
        $user = (new User())->setEmail('testr@test.com');
        $provider = new JwtUserProvider($this->userRepository);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'testr@test.com'])
            ->willReturn($user);

        $this->assertEquals($user, $provider->loadUserByIdentifier('testr@test.com'));
    }

    final public function testLoadUserByIdentifierNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'testr@test.com'])
            ->willReturn(null);

        (new JwtUserProvider($this->userRepository))->loadUserByIdentifier('testr@test.com');
    }

    final public function testLoadUserByIdentifierAndPayload(): void
    {
        $user = (new User())->setEmail('testr@test.com');
        $provider = new JwtUserProvider($this->userRepository);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($user);

        $this->assertEquals($user, $provider->loadUserByIdentifierAndPayload('testr@test.com', ['id' => 1]));

    }

    final public function testLoadUserByIdentifierAndPayloadNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' =>' 1'])
            ->willReturn(null);

        (new JwtUserProvider($this->userRepository))->loadUserByIdentifierAndPayload('testr@test.com', ['id' => 1]);
    }
}
