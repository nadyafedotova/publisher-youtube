<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Nelmio\ApiDocBundle\Annotation\Model;
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
    #[OA\RequestBody(attachables: [new Model(type: SubscriberRequest::class)])]
    #[Route(path: '/api/v1/subscribe', methods: ['POST'])]
    final public function subscribe(#[RequestBody] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(null);
    }
}
