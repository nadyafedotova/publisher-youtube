<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractControllerTest extends WebTestCase
{
    use JsonAssertions;

    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;

    protected UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->passwordHasher = self::getContainer()->get('security.user_password_hasher');
        $this->em->beginTransaction();
    }

    final protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;

        restore_exception_handler();
    }

    final protected function auth(string $username, string $password): void
    {
        $this->client->request(
            'POST',
            '/api/v1/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }

    final protected function createUser(string $username, string $password): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_USER']);
    }

    final protected function createAdmin(string $username, string $password): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_ADMIN']);
    }

    final protected function createAuthor(string $username, string $password): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_AUTHOR']);
    }

    final protected function createAdminAndAuth(string $username, string $password): User
    {
        $user = $this->createAdmin($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    final protected function createAuthorAndAuth(string $username, string $password): User
    {
        $user = $this->createAuthor($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    final protected function createUserAndAuth(string $username, string $password): User
    {
        $user = $this->createUser($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    private function createUserWithRoles(string $username, string $password, array $roles): User
    {
        $user = (new User())
            ->setRoles($roles)
            ->setLastName($username)
            ->setFirstName($username)
            ->setEmail($username);

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
