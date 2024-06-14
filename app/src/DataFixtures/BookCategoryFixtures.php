<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    final public const string ANDROID_CATEGORY = 'android';

    final public const string DEVICES_CATEGORY = 'devices';

    final public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            self::DEVICES_CATEGORY => ['title' => 'Devices', 'slug' => 'devices'],
            self::ANDROID_CATEGORY => ['title' => 'Android', 'slug' => 'android'],
        ];

        $categories = [];

        foreach ($categoriesData as $code => $data) {
            $category = new BookCategory();
            $category->setTitle($data['title']);
            $category->setSlug($data['slug']);
            $categories[$code] = $category;
            $manager->persist($category);
        }

        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }

    }
}
