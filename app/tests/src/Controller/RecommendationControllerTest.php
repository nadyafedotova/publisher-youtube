<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\EntityTest;
use GuzzleHttp\Exception\GuzzleException;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;
use JsonMapper_Exception;
use ReflectionException;

class RecommendationControllerTest extends AbstractControllerTest
{
    private EntityTest $entityTest;
    private HoverflyClient $hoverflyClient;

    /**
     * @throws JsonMapper_Exception
     * @throws GuzzleException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityTest = new EntityTest();
        $this->setUpHoverfly();
    }

    /**
     * @throws ReflectionException
     */
    final public function testRecommendationByBookId(): void
    {
        $book = $this->entityTest->createBook();
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
        } catch (GuzzleException|JsonMapper_Exception $e) {
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

    /**
     * @throws JsonMapper_Exception
     * @throws GuzzleException
     */
    private function setUpHoverfly(): void
    {
        $this->hoverflyClient = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverflyClient->deleteJournal();

        $this->hoverflyClient->deleteSimulation();
    }
}
