<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\AccountStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher){
    }
    public function load(ObjectManager $manager): void
    {
        $app = new User();

        $app->setUsername('ghost');
        $app->setEmail('ghost@mail.com');
        $app->setPassword($this->passwordHasher->hashPassword($app, 'test'));
        $app->setAccountStatus(AccountStatus::DISPONIBLE);
//        $app->setSubscription(Subscription::class, 'prenium');

        $manager->persist($app);
        $manager->flush();
    }

}
