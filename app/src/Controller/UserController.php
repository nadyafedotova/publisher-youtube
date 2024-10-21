<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SingUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(
        readonly private SingUpService $singUpService
    ) {
    }

    #[Route(path: '/api/v1/user/me', methods: ['GET'])]
    final public function me(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($user);
    }
}
