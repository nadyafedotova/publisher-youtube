<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\EntityTest;
use ReflectionException;

class ReviewControllerTest extends AbstractControllerTest
{
    private EntityTest $entityTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityTest = new EntityTest();
    }

    /**
     * @throws ReflectionException
     */
    final public function testReviews(): void
    {
        $book = $this->entityTest->createBook();
        $this->entityTest->createReview($book);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/' .$book->getId().'/review');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
                'properties' => [
                    'rating' => ['type' => 'number'],
                    'page' => ['type' => 'integer'],
                    'pages' => ['type' => 'integer'],
                    'perPage' => ['type' => 'integer'],
                    'total' => ['type' => 'integer'],
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'required' => ['id', 'content', 'author', 'rating', 'createdAt'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'content' => ['type' => 'string'],
                                'author' => ['type' => 'string'],
                                'rating' => ['type' => 'integer'],
                                'createdAt' => ['type' => 'integer'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
