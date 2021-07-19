<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // create 2 Users !
        // User 1 : ROLE_ADMIN
        $user = new User();
        $user->setName('Slimane')
            ->setEmail('admin2@admin.fr')
            ->setRoles(["ROLE_ADMIN"])
            ->setisVerified(true)
            ->setPassword($this->encoder->encodePassword($user, 'Moi123456'));
        $manager->persist($user);

        // User 2
        $user = new User();
        $user->setName('toto2')
            ->setEmail('toto2@gmail.com')
            ->setisVerified(true)
            ->setPassword($this->encoder->encodePassword($user, 'toto123456'));
        $manager->persist($user);

        $manager->flush();
    }
}
