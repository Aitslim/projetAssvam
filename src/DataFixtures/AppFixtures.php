<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\Category;
use App\Entity\Evaluation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Nécessite : composer require fakerphp/faker
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 2 Users !
        // User 1 : ROLE_ADMIN
        $user1 = new User();
        $user1->setName('Slimane')
            ->setEmail('admin1@admin.fr')
            ->setRoles(["ROLE_ADMIN"])
            ->setisVerified(true)
            ->setIsSuspended(false)
            ->setPassword($this->encoder->hashPassword($user1, 'Moi123456'));
        $manager->persist($user1);

        // User 2
        $user2 = new User();
        $user2->setName('toto1')
            ->setEmail('toto1@gmail.com')
            ->setisVerified(true)
            ->setIsSuspended(false)
            ->setPassword($this->encoder->hashPassword($user2, 'toto123456'));
        $manager->persist($user2);

        // Autres 4 Users
        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setName($faker->name())
                ->setEmail($faker->email())
                ->setisVerified(true)
                ->setIsSuspended(false)
                ->setPassword($this->encoder->hashPassword($user, 'toto123456'));
            $manager->persist($user);
        }

        // Les 8 Categories !
        $categorie = [
            ['Nature'],
            ['Evénement'],
            ['Culture'],
            ['Actualité'],
            ['Ecologie'],
            ['Poésie'],
            ['Litérature'],
            ['Spectacle']
        ];

        // Créer le 8 Categories !
        for ($i = 0; $i <= 7; $i++) {

            $category = new Category();
            $category->setName($categorie[$i][0]);

            $manager->persist($category);

            // Créer entre 3 et 8 Articles/Catégorie !
            for ($j = 0; $j <= rand(2, 5); $j++) {

                $post = new Post();
                $post->setTitle($faker->sentence(5))
                    ->setContent($faker->paragraph(80, false))
                    ->setCategory($category)
                    ->setUser((rand(1, 8) > 5) ? $user1 : $user2)
                    ->setActive(true)
                ;

                $manager->persist($post);
            }
        }

        // Créer 4 Projects
        for ($i = 0; $i <= 4; $i++) {

            $project = new Project();
            $project->setDescription($faker->sentence(2))
                ->setName($faker->sentence(3))
                ->setUser((rand(1, 8) > 5) ? $user1 : $user2)
                ->setBudget(true)
                ->setPubliccible(false)
                ->setSponsor(true);

            $manager->persist($project);

            // Entre 2 et 5 evaluations/projet !
            for ($j = 0; $j <= rand(2, 5); $j++) {

                $evaluation = new Evaluation();
                $evaluation->setAppreciation($faker->sentence(10))
                    ->setNote($faker->numberBetween(0, 10))
                    ->setProject($project)
                    ->setUser((rand(1, 8) > 5) ? $user1 : $user2)
                    ->setProject($project);

                $manager->persist($evaluation);
            }
        }

        $manager->flush();
    }
}
