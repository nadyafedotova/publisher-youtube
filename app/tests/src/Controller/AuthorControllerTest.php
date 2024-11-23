<?php

namespace App\Tests\src\Controller;

use App\Entity\User;
use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use JsonException;
use Random\RandomException;
use ReflectionException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AuthorControllerTest extends AbstractControllerTest
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createAuthorAndAuth('user@test.com', 'testtest');
    }

    /** @throws JsonException */
    final public function testCreateBook(): void
    {
        $this->client->request('POST', '/api/v1/author/book', [], [], [], json_encode([
            'title' => 'Test book',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    /** @throws OptimisticLockException|ORMException|JsonException */
    final public function testUploadBookCover(): void
    {
        $book = MockUtils::createBook()->setUser($this->user)->setImage(null);

        $this->em->persist($book);
        $this->em->flush();

        $fixturePath = __DIR__ . '/../Fixtures/logo_light_white.png';
        $clonedImagePath = sys_get_temp_dir() . PATH_SEPARATOR . 'test.png';

        (new Filesystem())->copy($fixturePath, $clonedImagePath);

        $uploadedFile = new UploadedFile(
            $clonedImagePath,
            'test.png',
            'image/png',
            null,
            true,
        );

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/uploadCover', [], [
            'cover' => $uploadedFile,
        ]);

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['link'],
            'properties' => [
                'link' => ['type' => 'string'],
            ],
        ]);
    }

    /** @throws OptimisticLockException|ORMException */
    final public function testDeleteBook(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);

        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('DELETE', '/api/v1/author/book/' . $book->getId());

        $this->assertResponseIsSuccessful();
    }

    /** @throws ReflectionException|OptimisticLockException|ORMException */
    final public function testUpdateBook(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $category = MockUtils::createBookCategory();
        $format = MockUtils::createBookFormat();

        $this->em->persist($book);
        $this->em->persist($format);
        $this->em->persist($category);
        $this->em->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId(), [], [], [], json_encode([
            'title' => 'Updated book',
            'authors' => ['vasya'],
            'description' => 'testing updated book',
            'category' => [$category->getId()],
            'format' => [['id' => $format->getId(), 'price' => 123.5, 'discountPercent' => 5]],
        ]));

        $this->assertResponseIsSuccessful();
    }

    /** @throws OptimisticLockException|ORMException */
    final public function testPublishBook(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);

        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/publish', [], [], [], json_encode([
            'dateTime' => '2010-02-22T00:00:00',
        ]));

        $this->assertResponseIsSuccessful();
    }

    /** @throws OptimisticLockException|ORMException */
    final public function testUnpublishBook(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);

        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/unpublish');

        $this->assertResponseIsSuccessful();
    }

    /** @throws OptimisticLockException|ORMException|JsonException */
    final public function testBooks(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);

        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/author/books');

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['bookCategoryList'],
            'properties' => [
                'bookCategoryList' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'image' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @throws ReflectionException|OptimisticLockException|ORMException|JsonException */
    final public function testBook(): void
    {
        $category = MockUtils::createBookCategory();
        $format = MockUtils::createBookFormat();
        $book = MockUtils::createBook()->setUser($this->user)->setCategories(new ArrayCollection([$category]));
        $join = MockUtils::createBookFormatLink($book, $format);

        $this->em->persist($category);
        $this->em->persist($format);
        $this->em->persist($book);
        $this->em->persist($join);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/author/book/' . $book->getId());

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'slug', 'isbn', 'title', 'authors', 'categories', 'formats', 'description', 'image', 'publicationDate',
            ],
            'properties' => [
                'id' => ['type' => 'integer'],
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'image' => ['type' => 'string'],
                'isbn' => ['type' => 'string'],
                'authors' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'description' => ['type' => 'string'],
                'publicationDate' => ['type' => 'integer'],
                'categories' => [
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
                'formats' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'description', 'comment', 'price', 'discountPercent'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'comment' => ['type' => ['string', 'null']],
                            'price' => ['type' => 'number'],
                            'discountPercent' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @throws OptimisticLockException|ORMException|JsonException */
    final public function testCreateBookChapter(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);

        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/chapter', [], [], [], json_encode([
            'title' => 'Test book',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    /** @throws OptimisticLockException|ORMException|JsonException */
    final public function testUpdateBookChapter(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->flush();

        $url = '/api/v1/author/book/' . $book->getId() . '/chapter/' . $chapter->getId();
        $this->client->request('POST', $url, [], [], [], json_encode(['title' => 'Update Book Chapter'], JSON_THROW_ON_ERROR));

        $this->assertResponseIsSuccessful();
    }

    /** @throws RandomException|OptimisticLockException|ORMException|JsonException */
    final public function testUpdateBookChapterSort(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapterFirst = MockUtils::createBookChapter($book);
        $chapterSecond = MockUtils::createBookChapter($book);
        $chapterThird = MockUtils::createBookChapter($book);

        $this->em->persist($book);
        $this->em->persist($chapterFirst);
        $this->em->persist($chapterSecond);
        $this->em->persist($chapterThird);
        $this->em->flush();

        $url = '/api/v1/author/book/' . $book->getId() . '/chapter/' . $chapterFirst->getId() . '/sort';
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            [],
            json_encode([
                'nextId' => $chapterThird->getId(),
                'previousId' => $chapterSecond->getId(),
            ], JSON_THROW_ON_ERROR),
        );

        $this->assertResponseIsSuccessful();
    }

    /** @throws RandomException|OptimisticLockException|ORMException|JsonException */
    final public function testGetBookChapterTree(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapterMain = MockUtils::createBookChapter($book);
        $chapterNested = MockUtils::createBookChapter($book)
            ->setLevel(2)
            ->setParent($chapterMain)
            ->setSort(2);

        $this->em->persist($book);
        $this->em->persist($chapterMain);
        $this->em->persist($chapterNested);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/author/book/' . $book->getId() . '/chapters');

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'items'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'required' => ['id', 'title', 'slug', 'items'],
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'title' => ['type' => 'string'],
                                        'slug' => ['type' => 'string'],
                                    ],
                                ],
                            ],

                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @throws RandomException|OptimisticLockException|ORMException */
    final public function testDeleteBookChapter(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->flush();

        $this->client->request('DELETE', '/api/v1/author/book/' . $book->getId() .'/chapter/' . $chapter->getId());

        $this->assertResponseIsSuccessful();
    }

    /** @throws RandomException|OptimisticLockException|ORMException|JsonException */
    final public function testCreateBookContent(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content', $book->getId(), $chapter->getId());
        $this->client->request('POST', $url, [], [], [], json_encode(['content' => 'test content', 'published' => true]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer']
            ],
        ]);
    }

    /** @throws OptimisticLockException|ORMException */
    final public function testUpdateBookContent(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->persist($content);
        $this->em->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content/%d', $book->getId(), $chapter->getId(), $content->getId());
        $this->client->request('DELETE', $url, [], [], [], json_encode(['content' => 'new test content', 'published' => false]));

        $this->assertResponseIsSuccessful();
    }

    /** @throws OptimisticLockException|ORMException */
    final public function testDeleteBookChapterContent(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->persist($content);
        $this->em->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content/%d', $book->getId(), $chapter->getId(), $content->getId());
        $this->client->request('DELETE', $url);

        $this->assertResponseIsSuccessful();
    }

    /** @throws RandomException|OptimisticLockException|ORMException|JsonException */
    final public function testChapterContent(): void
    {
        $book = MockUtils::createBook()->setUser($this->user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);
        $unpublishedContent = MockUtils::createBookContent($chapter)->setIsPublished(false);

        $this->em->persist($book);
        $this->em->persist($chapter);
        $this->em->persist($content);
        $this->em->persist($unpublishedContent);
        $this->em->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content', $book->getId(), $chapter->getId());
        $this->client->request('GET', $url);

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, ['$.items' => self::countOf(2)]);
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPages' => ['type' => 'integer'],
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
        ]);
    }
}
