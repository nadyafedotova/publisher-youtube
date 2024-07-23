<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Book;
use App\Model\BookDetails;
use App\Model\BookListItem;
use App\Model\RecommendedBook;

class BookMapper
{
    public static function map(Book $book, BookDetails|BookListItem $model): BookDetails|BookListItem
    {
        $model->setId($book->getId());
        $model->setTitle($book->getTitle());
        $model->setSlug($book->getSlug());
        $model->setImage($book->getImage());
        $model->setAuthors($book->getAuthors());
        $model->setMeap($book->isMeap());
        $model->setPublicationDate($book->getPublicationDate()->getTimestamp());

        return $model;
    }

    public static function mapRecommended(Book $book): RecommendedBook
    {
        $description = $book->getDescription();
        $description = strlen($description) > 150 ? substr($description, 0, 150) . "..." : $description;

        $recommendedBook = new RecommendedBook();
        $recommendedBook->setId($book->getId());
        $recommendedBook->setImage($book->getImage());
        $recommendedBook->setSlug($book->getSlug());
        $recommendedBook->setTitle($book->getTitle());
        $recommendedBook->setShortDescription($description);

        return $recommendedBook;
    }
}
