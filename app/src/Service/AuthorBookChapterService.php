<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookChapter;
use App\Model\Author\UpdateBookChapterSortRequest;
use App\Model\BookChapter as BookChapterModel;
use App\Exception\BookChapterInvalidSortException;
use App\Model\Author\CreateBookChapterRequest;
use App\Model\Author\UpdateBookChapterRequest;
use App\Model\BookChapterTreeResponse;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class AuthorBookChapterService
{
    private const int MAX_LEVEL = 3;
    private const int MIN_LEVEL = 1;
    private const int SORT_STEP = 1;

    public function __construct(
        private BookRepository        $bookRepository,
        private BookChapterRepository $bookChapterRepository,
        private BookChapterService    $bookChapterService,
        private SluggerInterface      $slugger,
    ) {
    }

    final public function createChapter(CreateBookChapterRequest $request, int $bookId): IdResponse
    {
        $book = $this->bookRepository->getBookById($bookId);
        $title = $request->getTitle();
        $parentId = $request->getParentId();
        $parent = null;
        $level = self::MIN_LEVEL;

        if (null !== $parentId) {
            $parent = $this->bookChapterRepository->getById($parentId);
            $parentLevel = $parent->getLevel();

            if (self::MAX_LEVEL === $parentLevel) {
                throw new BookChapterInvalidSortException('max level is reached');
            }

            $level = $parentLevel + 1;
        }

        $chapter = (new BookChapter())
            ->setTitle($title)
            ->setSlug($this->slugger->slug($title))
            ->setParent($parent)
            ->setLevel($level)
            ->setSort($this->getNextMaxSort($book, $level))
            ->setBook($book);

        $this->bookChapterRepository->saveAndCommit($chapter);

        return new IdResponse($chapter->getId());
    }

    final public function updateChapter(UpdateBookChapterRequest $request): void
    {
        $chapter = $this->bookChapterRepository->find($request->getId());
        $title = $request->getTitle();
        $chapter->setTitle($title)->setSlug($this->slugger->slug($title));

        $this->bookChapterRepository->commit();
    }

    final public function deleteChapter(IdResponse $idResponse): void
    {
        $chapter = $this->bookChapterRepository->find($idResponse);

        $this->bookChapterRepository->removeAndCommit($chapter);
    }

    final public function getChaptersTree(int $bookId): BookChapterTreeResponse
    {
        return $this->bookChapterService->getChaptersTree($this->bookRepository->getBookById($bookId));
    }

    final public function updateChapterSort(UpdateBookChapterSortRequest $request, int $id): void
    {
        $chapter = $this->bookChapterRepository->getById($id);
        $sortContext = SortContext::fromNeighbours($request->getNextId(), $request->getPreviousId());
        $nearChapter = $this->bookChapterRepository->getById($sortContext->getNearId());
        $level = $nearChapter->getLevel();

        if (SortPosition::AsLast === $sortContext->getPosition()) {
            $sort = $this->getNextMaxSort($chapter->getBook(), $level);
        } else {
            $sort = $nearChapter->getSort();
            $this->bookChapterRepository->increasesSortFrom($sort, $chapter->getBook(), $level, self::SORT_STEP);
        }

        $chapter->setLevel($level)->setSort($sort)->setParent($nearChapter->getParent());

        $this->bookChapterRepository->commit();
    }

    private function getNextMaxSort(Book $book, int $level): int
    {
        return $this->bookChapterRepository->getMaxSort($book, $level) + self::SORT_STEP;
    }
}
