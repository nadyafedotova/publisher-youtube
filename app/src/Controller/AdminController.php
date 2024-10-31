<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Service\RoleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    public function __construct(
        readonly private RoleService $roleService,
    ) {
    }

    #[OA\Tag(name: "Admin API")]
    #[OA\Response(
        response: 200,
        description: 'Grand ROLE_AUTHOR to a user',
    )]
    #[OA\Response(
        response: 404,
        description: 'User not Found',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[Route(path: '/api/v1/admin/grantAuthor/{userId}', methods: ['POST'])]
    final public function grantAuthor(int $userId): Response
    {
        $this->roleService->grantAuthor($userId);

        return $this->json(null);
    }

}
