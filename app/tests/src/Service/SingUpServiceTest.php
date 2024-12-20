<?php

namespace App\Tests\src\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use App\Service\SingUpService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use PHPUnit\Framework\MockObject\Exception;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class SingUpServiceTest extends AbstractTestCase
{
    private UserPasswordHasher $userPasswordHasher;
    private UserRepository $userRepository;
    private AuthenticationSuccessHandler $authenticationSuccessHandler;

    /** @throws Exception */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userPasswordHasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->authenticationSuccessHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }

    private function createService(): SingUpService
    {
        return new SingUpService($this->userPasswordHasher, $this->userRepository, $this->authenticationSuccessHandler);
    }

    final public function testSingUpUserAlreadyExists(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('tester@test.com')
            ->willReturn(true);

        $this->createService()->singUp(MockUtils::createSingUpRequest());
    }

    /** @throws RandomException */
    public function testSignUp(): void
    {
        $response = new Response();
        $expectedHasherUser = MockUtils::createUser()->setRoles(['ROLE_USER'])->setEmail('tester@test.com');

        $expectedUser = clone $expectedHasherUser;
        $expectedUser->setPassword('hashed_password');

        $expectedHashedPassword = 'hashed_password';
        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('tester@test.com')
            ->willReturn(false);

        $this->userPasswordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->callback(function (User $user) use ($expectedUser) {
                return $user->getEmail() === $expectedUser->getEmail()
                    && $user->getRoles() === $expectedUser->getRoles();
            }), $expectedHashedPassword)
            ->willReturn($expectedHashedPassword);

        $this->userRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedUser);

        $this->authenticationSuccessHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($this->callback(function (User $user) use ($expectedUser, $expectedHashedPassword) {
                return $user->getEmail() === $expectedUser->getEmail()
                    && $user->getPassword() === $expectedHashedPassword;
            }))
            ->willReturn($response);

        $singUpRequest = MockUtils::createSingUpRequest();

        $this->assertEquals($response, $this->createService()->singUp($singUpRequest));
    }
}
