<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\EntityTest;
use ReflectionException;

class RecommendationControllerTest extends AbstractControllerTest
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

        $this->client->request('GET', '/api/v1/book/' .$book->getId().'/recommendations');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
                'properties' => [
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'required' => ['id', 'title', 'slug', 'image', 'shortDescription'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'string'],
                                'slug' => ['type' => 'string'],
                                'image' => ['type' => 'string'],
                                'shortDescription' => ['type' => 'string'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
