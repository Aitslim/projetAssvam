<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLastPosts(int $nb = 10)
    {
        // A REVOIR : ajouter archive = true ou false

        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :status')
            ->setParameter('status', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    public function findOldPosts(int $nb = 5): array
    {
        $entityManager = $this->getEntityManager();

        // A REVOIR : ajouter archive = true ou false

        $query = $entityManager->createQuery(
            'SELECT p.id, p.title, p.createdAt, p.slug, p.image
            FROM App\Entity\Post p
            WHERE p.active = :status
            ORDER BY p.createdAt ASC'
        )
            ->setParameter('status', true)
            ->setMaxResults($nb);
        return $query->getResult();
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
