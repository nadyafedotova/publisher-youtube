<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    #[Route('/')]
    final public function index(): Response
    {
        $book = $this->bookRepository->findAll();

        return $this->json($book);
    }
}
