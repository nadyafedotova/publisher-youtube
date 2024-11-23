<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\ORM\Exception\ORMException;
use Random\RandomException;
use ReflectionException;

class ReviewControllerTest extends AbstractControllerTest
{
    /** @throws ReflectionException|RandomException|ORMException */
    final public function testReviews(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $book = MockUtils::createBook()->setUser($user);
        $this->em->persist($book);

        $review = MockUtils::createReview($book);

        $this->em->persist($review);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/' .$book->getId().'/reviews');
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
                            'required' => ['id', 'content', 'author', 'rating'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'content' => ['type' => 'string'],
                                'author' => ['type' => 'string'],
                                'rating' => ['type' => 'integer'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
