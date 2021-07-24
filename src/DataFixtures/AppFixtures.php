<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Nécessite : composer require fakerphp/faker
        $faker = \Faker\Factory::create('fr_FR');

        // create 2 Users !
        // User 1 : ROLE_ADMIN
        $user1 = new User();
        $user1->setName('Slimane')
            ->setEmail('admin1@admin.fr')
            ->setRoles(["ROLE_ADMIN"])
            ->setisVerified(true)
            ->setPassword($this->encoder->encodePassword($user1, 'Moi123456'));
        $manager->persist($user1);

        // User 2
        $user2 = new User();
        $user2->setName('toto1')
            ->setEmail('toto1@gmail.com')
            ->setisVerified(true)
            ->setPassword($this->encoder->encodePassword($user2, 'toto123456'));
        $manager->persist($user2);

        // Autres 7 Users
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName($faker->name())
                ->setEmail($faker->email())
                ->setisVerified(true)
                ->setPassword($this->encoder->encodePassword($user, 'toto123456'));
            $manager->persist($user);
        }

        // Create 8 Categories !
        $categorie = [
            ['Nature', null],
            ['Evénement', null],
            ['Culture', null],
            ['Actualité', null],
            ['Ecologie', null], // 1
            ['Poésie', null],   // 3
            ['Litérature', null], //3
            ['Spectacle', null] // 3
        ];

        for ($i = 0; $i <= 7; $i++) {

            $category = new Category();
            $category->setName($categorie[$i][0])
                ->setParent($categorie[$i][1]);

            $manager->persist($category);

            // Create entre 3 et 8 Articles/Catégorie !
            for ($j = 0; $j <= rand(2, 5); $j++) {

                $post = new Post();
                $post->setTitle($faker->sentence(5))
                    // ->setContent($faker->text())
                    ->setContent($faker->paragraph(80, false))
                    ->setCategory($category)
                    ->setUser((rand(1, 8) > 5) ? $user1 : $user2)
                    ->setActive(true)
                    ->setArchived(false);

                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}