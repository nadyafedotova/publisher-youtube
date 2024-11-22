<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BookContent;
use App\Model\Author\CreateBookChapterContentRequest;
use App\Model\BookChapterContent;
use App\Model\BookChapterContentPage;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookContentRepository;

readonly class BookContentService
{
    private const int PAGE_LIMIT = 30;

    public function __construct(
        private BookContentRepository $bookContentRepository,
        private BookChapterRepository $bookChapterRepository
    ) {
    }

    final public function createContent(CreateBookChapterContentRequest $request, int $chapterId): IdResponse
    {
        $content = new BookContent();
        $content->setChapter($this->bookChapterRepository->getById($chapterId));

        $this->saveContent($request, $content);

        return new IdResponse($content->getId());
    }

    final public function updateContent(CreateBookChapterContentRequest $request, int $id): void
    {
        $content = $this->bookContentRepository->getById($id);

        $this->saveContent($request, $content);
    }
    final public function deleteContent(int $id): void
    {
        $content = $this->bookContentRepository->getById($id);
        $this->bookContentRepository->removeAndCommit($content);
    }

    final public function getAllContent(int $chapterId, int $page): BookChapterContentPage
    {
        return $this->getContent($chapterId, $page, false);
    }

    final public function getPublishedContent(int $chapterId, int $page): BookChapterContentPage
    {
        return $this->getContent($chapterId, $page, true);
    }

    private function saveContent(CreateBookChapterContentRequest $request, BookContent $content): void
    {
        $content->setContent($request->getContent());
        $content->setIsPublished($request->getIsPublished());

        $this->bookContentRepository->saveAndCommit($content);
    }

    private function getContent(int $chapterId, int $page, bool $onlyPublished): BookChapterContentPage
    {
        $items = [];
        $paginator = $this->bookContentRepository->getPageByChapterId(
            $chapterId,
            $onlyPublished,
            PaginationUtils::calcOOffset($page, self::PAGE_LIMIT),
            self::PAGE_LIMIT
        );

        foreach ($paginator as $item) {
            $items[] = (new BookChapterContent())
                ->setId($item->getId())
                ->setContent($item->getContent())
                ->setIsPublished($item->isPublished());
        }

        $total = $this->bookContentRepository->countByChapterId($chapterId, $onlyPublished);

        return (new BookChapterContentPage())
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(self::PAGE_LIMIT)
            ->setItems($items);
    }
}
