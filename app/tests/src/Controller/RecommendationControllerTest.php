<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use GuzzleHttp\Exception\GuzzleException;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;
use JsonMapper_Exception;
use Random\RandomException;
use ReflectionException;

class RecommendationControllerTest extends AbstractControllerTest
{
    private HoverflyClient $hoverflyClient;

    final protected function setUp(): void
    {
        parent::setUp();
        $this->setUpHoverfly();
    }

    /**
     * @throws ReflectionException|RandomException
     */
    final public function testRecommendationByBookId(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $book = MockUtils::createBook();
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
//            dd($e);
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

    private function setUpHoverfly(): void
    {
        $this->hoverflyClient = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
    }
}
