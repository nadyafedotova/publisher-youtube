<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Model\SingUpRequest;
use App\Service\SingUpService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    public function __construct(
        readonly private SingUpService $singUpService
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Sing up a user',
        content: new OA\JsonContent(properties: [
            new OA\Property(
                property: 'token',
                type: "string",
            ),
            new OA\Property(
                property: 'refresh_token',
                type: "string",
            ),
        ])
    )]
    #[OA\Response(
        response: 409,
        description: 'User already exist',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\RequestBody(attachables: [new Model(type: SingUpRequest::class)])]
    #[Route(path: '/api/v1/auth/singUp', methods: ['POST'])]
    final public function singUp(#[RequestBody] SingUpRequest $singUpRequest): Response
    {
        return $this->singUpService->singUp($singUpRequest);
    }
}
