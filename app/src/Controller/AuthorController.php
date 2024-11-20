<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Model\Author\BookDetails;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookChapterContentRequest;
use App\Model\Author\CreateBookChapterRequest;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UpdateBookChapterRequest;
use App\Model\Author\UpdateBookChapterSortRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\BookChapterContentPage;
use App\Model\BookChapterTreeResponse;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Security\Voter\AuthorBookVoter;
use App\Service\AuthorBookChapterService;
use App\Service\AuthorBookService;
use App\Service\BookContentService;
use App\Service\BookPublishService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(
        readonly private AuthorBookService  $authorService,
        readonly private BookPublishService $bookPublishService,
        readonly private AuthorBookChapterService $bookChapterService,
        readonly private BookContentService $bookContentService,
    ) {
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Upload book cover', attachables: [new Model(type: UploadCoverResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[Route(path: '/api/v1/author/book/{id}/uploadCover', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function uploadCover(
        int $id,
        #[RequestFile(field: 'cover', constraints: [new NotNull(), new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),])] UploadedFile $file,
    ): Response {
        return $this->json($this->authorService->uploadCover($id, $file));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Publish a book')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: PublishBookRequest::class)])]
    #[Route(path: '/api/v1/author/book/{id}/publish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function publish(int $id, #[RequestBody] PublishBookRequest $publishBookRequest): Response
    {
        $this->bookPublishService->publish($id, $publishBookRequest);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Unpublish a book')]
    #[Route(path: '/api/v1/author/book/{id}/unpublish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function unpublish(int $id): Response
    {
        $this->bookPublishService->unpublish($id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Get authors owned books', content: new Model(type: BookListResponse::class))]
    #[Route(path: '/api/v1/author/books', methods: ['GET'])]
    final public function books(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($this->authorService->getBooks($user));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Create a book', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookRequest::class)])]
    #[Route(path: '/api/v1/author/book', methods: ['POST'])]
    final public function createBook(#[RequestBody] CreateBookRequest $bookRequest, #[CurrentUser] UserInterface $user): Response
    {
        return $this->json($this->authorService->createBook($bookRequest, $user));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Remove a book')]
    #[OA\Response(response: 404, description: 'Book not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/book/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Update a book')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookRequest::class)])]
    #[Route(path: '/api/v1/author/book/{id}', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function updateBook(int $id, #[RequestBody] UpdateBookRequest $updateBookRequest): Response
    {
        $this->authorService->updateBook($id, $updateBookRequest);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Get authors owned book', attachables: [new Model(type: BookDetails::class)])]
    #[OA\Response(response: 404, description: 'book not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[Route(path: '/api/v1/author/book/{id}', methods: ['GET'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    final public function book(int $id): Response
    {
        return $this->json($this->authorService->getBook($id));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Create a book chapter', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookChapterRequest::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/chapter', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function createBookChapter(#[RequestBody] CreateBookChapterRequest $request, int $bookId): Response
    {
        return $this->json($this->bookChapterService->createChapter($request, $bookId));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Sort a book chapter', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 404, description: 'Book chapter not found', content: new Model(type: ErrorResponse::class))]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookChapterSortRequest::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/chapterSort', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function updateBookChapterSort(#[RequestBody] UpdateBookChapterSortRequest $request, int $bookId): Response
    {
        $this->bookChapterService->updateChapterSort($request);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Update a book chapter', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 404, description: 'Book chapter not found', content: new Model(type: ErrorResponse::class))]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookChapterRequest::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/updateChapter', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function updateBookChapter(#[RequestBody] UpdateBookChapterRequest $request): Response
    {
        $this->bookChapterService->updateChapter($request);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Get book chapters as tree', attachables: [new Model(type: BookChapterTreeResponse::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/chapters', methods: ['GET'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function chapters(int $bookId): Response
    {
        return $this->json($this->bookChapterService->getChaptersTree($bookId));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Remove a book chapter')]
    #[OA\Response(response: 404, description: 'Book chapter not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/book/{bookId}/chapters/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function deleteBookChapter(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Create a book content', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookChapterContentRequest::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{chapterId}/content', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function createBookChapterContent(#[RequestBody] CreateBookChapterContentRequest $request, int $bookId, int $chapterId): Response
    {
        return $this->json($this->bookContentService->createContent($request, $chapterId));
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Remove a book chapter content')]
    #[OA\Response(response: 404, description: 'Book chapter content not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{chapterId}/content/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function deleteBookChapterContent(int $id, int $bookId): Response
    {
        $this->bookContentService->deleteContent($id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Update a book content', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 404, description: 'Book content not found', content: new Model(type: ErrorResponse::class))]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookChapterContentRequest::class)])]
    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{chapterId}/content/{id}', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function updateBookChapterContent(#[RequestBody] CreateBookChapterContentRequest $request, int $bookId, int $chapterId, int $id): Response
    {
        $this->bookContentService->updateContent($request, $id);

        return $this->json(null);
    }

    #[OA\Tag(name: "Author API")]
    #[OA\Response(response: 200, description: 'Get book chapter content', attachables: [new Model(type: BookChapterContentPage::class)])]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{chapterId}/content', methods: ['GET'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    final public function chapterContent(Request $request, int $bookId, int $chapterId): Response
    {
        return $this->json($this->bookContentService->getAllContent($chapterId, (int) $request->query->get('page', 1)));
    }
}
