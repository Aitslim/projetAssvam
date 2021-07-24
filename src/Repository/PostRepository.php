<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active_status')
            ->setParameter('active_status', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    public function findOldPosts(int $nb = 10): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id, p.title, p.createdAt, p.slug, p.imagefilename
            FROM App\Entity\Post p
            WHERE p.active = :active_status
            ORDER BY p.createdAt ASC'
        )
            ->setParameter('active_status', true)
            ->setMaxResults($nb);

        return $query->getResult();
    }

    // A REVOIR : limit
    public function findAdminPosts(int $nb = 50)
    {
        return $this->createQueryBuilder('p')
            // ->andWhere('p.active = :active_status')
            // ->setParameter('active_status', true)
            ->orderBy('p.createdAt', 'DESC', 'p.active')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findPostsByTitle(string $value)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

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
