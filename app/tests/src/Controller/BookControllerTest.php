<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use JsonException;
use Random\RandomException;
use ReflectionException;

class BookControllerTest extends AbstractControllerTest
{
    /** @throws ReflectionException|RandomException */
    final public function testBooksByCategory(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);
        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/category/' . $bookCategory->getId() . '/books');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['bookCategoryList'],
                'properties' => [
                    'bookCategoryList' => [
                        'type' => 'array',
                        'bookCategoryList' => [
                            'type' => 'string',
                            'required' => ['id', 'title', 'slug', 'image', 'authors', 'publicationDate'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'string'],
                                'slug' => ['type' => 'string'],
                                'image' => ['type' => 'string'],
                                'authors' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        );
    }

    /** @throws ReflectionException|RandomException */
    final public function testBookById(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);

        $format = MockUtils::createBookFormat();
        $this->em->persist($format);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);

        $this->em->persist($book);
        $this->em->persist(MockUtils::createBookFormatLink($book, $format));
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/' . $book->getId());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'id', 'title', 'slug', 'image', 'authors', 'publicationDate', 'rating', 'reviews',
                'categories', 'formats', 'chapters',
            ],
            'properties' => [
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'id' => ['type' => 'integer'],
                'publicationDate' => ['type' => 'integer'],
                'image' => ['type' => 'string'],
                'authors' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'rating' => ['type' => 'number'],
                'reviews' => ['type' => 'integer'],
                'chapters' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'items'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
                'categories' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @throws ReflectionException|RandomException|JsonException */
    final public function testChapterContent(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $book = MockUtils::createBook()->setUser($user);
        $this->em->persist($book);

        $bookChapter = MockUtils::createBookChapter($book);
        $this->em->persist($bookChapter);

        $bookContent = MockUtils::createBookContent($bookChapter);
        $this->em->persist($bookContent);

        $unpublishedBookContent = MockUtils::createBookContent($bookChapter)->setIsPublished(false);
        $this->em->persist($unpublishedBookContent);

        $this->em->flush();

        $url = sprintf('/api/v1/book/%d/chapter/%d/content', $book->getId(), $bookChapter->getId());

        $this->client->request('GET', $url);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, ['$.items' => self::countOf(1)]);
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['items', 'page', 'pages', 'perPage', 'total'],
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pages' => ['type' => 'integer'],
                    'perPage' => ['type' => 'integer'],
                    'total' => ['type' => 'integer'],
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'required' => ['id', 'content', 'published'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'content' => ['type' => 'string'],
                                'published' => ['type' => 'boolean'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
