<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const string USER_REFERENCE = 'user';

    final public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('test@test.com')
            ->setFirstName('Test')
            ->setLastName('Testerov')
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('password');

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}
