<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        $dataCategory = new BookCategory();
        $dataCategory->setTitle('Data')->setSlug('data');
        $manager->persist($dataCategory);

        $androidCategory = new BookCategory();
        $androidCategory->setTitle('Android')->setSlug('android');
        $manager->persist($androidCategory);

        $networkingCategory = new BookCategory();
        $networkingCategory->setTitle('Networking')->setSlug('networking');
        $manager->persist($networkingCategory);

        $manager->flush();
    }
}
