<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Response;

class SubscribeControllerTest extends AbstractControllerTest
{
    final public function testSubscribe(): void
    {
        $content = json_encode(['email' => 'test@test.com', 'agreed' => true]);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);

        $this->assertResponseIsSuccessful();
    }

    final public function testSubscribeNotAgreed(): void
    {
        $content = json_encode(['email' => 'test@test.com']);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);
        $responseContent = json_decode($this->client->getResponse()->getContent());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            '$.messages' => 'validation failed',
            '$.details.violations' => self::countOf(1),
            '$.details.violations[0].failed' => 'agreed',
        ]);
    }
}
