<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\ORM\Exception\ORMException;
use GuzzleHttp\Exception\GuzzleException;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;
use JsonMapper_Exception;
use Random\RandomException;

class RecommendationControllerTest extends AbstractControllerTest
{
    private HoverflyClient $hoverflyClient;

    /** @throws JsonMapper_Exception|GuzzleException */
    final protected function setUp(): void
    {
        parent::setUp();
        $this->setUpHoverfly();
    }

    /** @throws RandomException|ORMException */
    final public function testRecommendationByBookId(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $book = MockUtils::createBook()->setUser($user);
        $this->em->persist($book);

        $this->em->flush();

        $requestedId = 123;

        try {
            $this->hoverflyClient->simulate(
                $this->hoverflyClient->buildSimulation()
                    ->service()
                    ->get(new RequestFieldMatcher(
                        '/api/v1/book/' . $requestedId . '/recommendations',
                        RequestFieldMatcher::GLOB
                    ))
                    ->headerExact('Authorization', 'Bearer test')
                    ->willReturn(Response::json(
                        [
                            'ts' => 12345,
                            'id' => $requestedId,
                            'recommendations' => [['id' => $book->getId()]],
                        ]
                    )),
            );
        } catch (GuzzleException|JsonMapper_Exception) {
        }

        $this->client->request('GET', '/api/v1/book/' . $requestedId . '/recommendations');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['items'],
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
            ],
        );
    }

    /** @throws JsonMapper_Exception|GuzzleException */
    private function setUpHoverfly(): void
    {
        $this->hoverflyClient = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverflyClient->deleteJournal();
        $this->hoverflyClient->deleteSimulation();
    }
}
