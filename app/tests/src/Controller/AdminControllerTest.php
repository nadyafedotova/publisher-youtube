<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use JsonException;
use ReflectionException;

class AdminControllerTest extends AbstractControllerTest
{
    final public function testGrantAuthor(): void
    {
        $user = $this->createUser('user@test.com', 'testtest');

        $this->createAdminAndAuth('admin@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/grantAuthor/'.$user->getId());

        $this->assertResponseIsSuccessful();
    }

    /** @throws OptimisticLockException|ReflectionException|ORMException */
    final public function testDeleteCategory(): void
    {
        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('DELETE', '/api/v1/admin/bookCategory/'.$bookCategory->getId());

        $this->assertResponseIsSuccessful();
    }

    /** @throws JsonException */
    final public function testCreateCategory(): void
    {
        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/bookCategory', [], [], [], json_encode([
            'title' => 'test',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ]
        ]);
    }

    /** @throws OptimisticLockException|ReflectionException|ORMException */
    final public function testUpdateCategory(): void
    {
        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/bookCategory/'.$bookCategory->getId(), [], [], [], json_encode([
            'title' => 'test 2',
        ]));

        $this->assertResponseIsSuccessful();
    }
}
