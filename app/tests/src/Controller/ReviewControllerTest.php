<?php

namespace App\Tests\src\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;

class ReviewControllerTest extends AbstractControllerTest
{
    public function testReviews(): void
    {
        $book = $this->createBook();

        $this->createReview($book);

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

    private function createReview(Book $book)
    {
        $review = new Review();
        $review->setAuthor('tester');
        $review->setContent('test content');
        $review->setCreatedAt(new DateTimeImmutable());
        $review->setRating(5);
        $review->setBook($book);
    }
}
