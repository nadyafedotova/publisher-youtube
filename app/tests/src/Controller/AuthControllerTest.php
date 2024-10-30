<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends AbstractControllerTest
{
    final public function testSingUp(): void
    {
        $this->client->request('POST', '/api/v1/auth/singUp', [], [], [], json_encode([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@test.com',
            'password' => 'password',
            'confirmPassword' => 'password',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();

        self::assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['token', 'refresh_token'],
            'properties' => [
                'token' => ['type' => 'string'],
                'refresh_token' => ['type' => 'string'],
            ],
        ]);
    }
}
