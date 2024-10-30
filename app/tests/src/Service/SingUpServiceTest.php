<?php

namespace App\Tests\src\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SingUpRequest;
use App\Repository\UserRepository;
use App\Service\SingUpService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class SingUpServiceTest extends AbstractTestCase
{
    private UserPasswordHasher $userPasswordHasher;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private AuthenticationSuccessHandler $authenticationSuccessHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userPasswordHasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->authenticationSuccessHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }

    private function createService(): SingUpService
    {
        return new SingUpService($this->userPasswordHasher, $this->userRepository, $this->entityManager, $this->authenticationSuccessHandler);
    }

    public function testSingUpUserAlreadyExists(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('tester@test.com')
            ->willReturn(true);

        $this->createService()->singUp((new SingUpRequest())->setEmail('tester@test.com'));
    }
    public function testSingUp(): void
    {
        $response = new Response();
        $expectedHasherUser = (new User())
            ->setRoles(['ROLE_USER'])
            ->setFirstName('Tester')
            ->setLastName('Tester')
            ->setEmail('tester@test.com');

        $expectedUser = clone $expectedHasherUser;
        $expectedUser->setPassword('hashed_password');

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('tester@test.com')
            ->willReturn(false);

        $this->userPasswordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($expectedHasherUser, 'testtest')
            ->willReturn('hashed_password');

        $this->entityManager->expects($this->once())->method('persist')->with($expectedUser);
        $this->entityManager->expects($this->once())->method('flush');

        $this->authenticationSuccessHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($expectedUser)
            ->willReturn($response);

        $singUpRequest = (new SingUpRequest())
            ->setFirstName('Tester')
            ->setLastName('Tester')
            ->setEmail('tester@test.com')
            ->setPassword('testtest');

        $this->assertEquals($response, $this->createService()->singUp($singUpRequest));
    }
}
