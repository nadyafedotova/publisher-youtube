<?php

namespace App\Tests\src\Controller;

use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;

class BookCategoryControllerTest extends AbstractControllerTest
{
    final public function testCategories(): void
    {
        $bookCategory = new BookCategory();
        $bookCategory->setTitle('Devices1')->setSlug('devices1');
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/categories');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

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
