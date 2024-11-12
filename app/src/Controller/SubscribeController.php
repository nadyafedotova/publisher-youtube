<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscribeController extends AbstractController
{
    public function __construct(
        readonly private SubscriberService $subscriberService
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Subscribe email to newsletter mailing list',
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [new Model(type: ErrorResponse::class)]
    )]
    #[OA\RequestBody(attachables: [new Model(type: SubscriberRequest::class)])]
    #[Route(path: '/api/v1/subscribe', methods: ['POST'])]
    final public function subscribe(#[RequestBody] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(null);
    }
}
