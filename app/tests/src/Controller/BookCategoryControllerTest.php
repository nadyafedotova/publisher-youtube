<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use JsonException;
use ReflectionException;

class BookCategoryControllerTest extends AbstractControllerTest
{
    /** @throws ReflectionException|JsonException */
    final public function testCategories(): void
    {
        $this->em->persist(MockUtils::createBookCategory());
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/categories');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['bookCategoryList'],
            'properties' => [
                'bookCategoryList' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
