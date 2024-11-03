<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\BookCategoryUpdateRequest;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Service\BooksCategoryService;
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
        readonly private BooksCategoryService $booksCategoryService,
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

    #[OA\Tag(name: "Admin API")]
    #[OA\Response(
        response: 200,
        description: 'Delete a book category',
    )]
    #[OA\Response(
        response: 404,
        description: 'Book category not found',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\Response(
        response: 400,
        description: 'Book category still contains books',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[Route(path: '/api/v1/admin/bookCategory/{id}', methods: ['DELETE'])]
    public function deleteCategory(int $id): Response
    {
        $this->booksCategoryService->deleteCategory($id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Admin API")]
    #[OA\Response(
        response: 200,
        description: 'Create a new category',
        attachables: [new Model(type: IdResponse::class)]
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\Response(
        response: 409,
        description: 'Book category already exists',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\RequestBody(attachables: [new Model(type: BookCategoryUpdateRequest::class)])]
    #[Route(path: '/api/v1/admin/bookCategory', methods: ['POST'])]
    public function createCategory(#[RequestBody] BookCategoryUpdateRequest $bookCategoryUpdateRequest): Response
    {
        return $this->json($this->booksCategoryService->createCategory($bookCategoryUpdateRequest));
    }

    #[OA\Tag(name: "Admin API")]
    #[OA\Response(
        response: 200,
        description: 'Update a book category',
        attachables: [new Model(type: IdResponse::class)]
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\Response(
        response: 409,
        description: 'Book category already exists',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\RequestBody(attachables: [new Model(type: BookCategoryUpdateRequest::class)])]
    #[Route(path: '/api/v1/admin/bookCategory/{id}', methods: ['POST'])]
    public function updateCategory(int $id, #[RequestBody] BookCategoryUpdateRequest $bookCategoryUpdateRequest): Response
    {
        $this->booksCategoryService->updateCategory($id, $bookCategoryUpdateRequest);

        return $this->json(null);
    }
}
