<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;

class SubscribeControllerTest extends AbstractControllerTest
{
    final public function testSubscribe(): void
    {
        $content = json_encode(['email' => 'test@test.com', 'agreed' => true]);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);

        $this->assertResponseIsSuccessful();
    }

//    /**
//     * @throws JsonException
//     */
//    final public function testSubscribeNotAgreed(): void
//    {
//        $content = json_encode(['email' => 'test@test.com']);
//        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);
//        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);
//
//        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
//        $this->assertJsonDocumentMatches($responseContent, [
//            '$.message' => 'validation failed',
//            '$.details.violations' => self::countOf(1),
//            '$.details.violations[0].field' => 'agreed',
//        ]);
//    }
}
