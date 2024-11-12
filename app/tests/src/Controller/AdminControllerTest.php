<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;

class AdminControllerTest extends AbstractControllerTest
{
    final public function testGrantAuthor(): void
    {
        $user = $this->createAdmin('test1@test.com', 'testtest');

        $username = 'test2@test.com';
        $password = 'testtest';
        $this->createAdmin($username, $password);
        $this->auth($username, $password);

        $this->client->request('POST', '/api/v1/admin/grantAuthor/'.$user->getId());

        $this->assertResponseIsSuccessful();
    }
}
