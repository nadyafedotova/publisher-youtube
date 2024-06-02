<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTest
{

    final public function testBooksByCategory(): void
    {
        $this->client->request('GET', '/api/v1/category/31/books');
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
                            'required' => ['id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'string'],
                                'slug' => ['type' => 'string'],
                                'image' => ['type' => 'string'],
                                'authors' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string'],
                                    ],
                                'publicationDate' => ['type' => 'integer'],
                                'meap' => ['type' => 'boolean'],
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    private function createCategory(): int
    {
        $bookCategory = new BookCategory();
        $bookCategory->setTitle('Devices')->setSlug('devices');
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->em->persist(
            (new Book())
            ->setTitle('Test Book')
            ->setImage('')
            ->setMeap(true)
            ->setPublicationDate(new DateTime('now'))
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setSlug('test-book')
        );


        return $bookCategory->getId();
    }
}
