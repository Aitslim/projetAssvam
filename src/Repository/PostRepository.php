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

    public function findLastPosts(int $nb = 20)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active_status')
            ->setParameter('active_status', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    public function findOldPosts(int $nb = 20): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id, p.title, p.content, p.createdAt, p.slug, p.imagefilename
            FROM App\Entity\Post p
            WHERE p.active = :active_status
            ORDER BY p.createdAt ASC'
        )
            ->setParameter('active_status', false)
            ->setMaxResults($nb);

        return $query->getResult();
    }

    public function findAdminPosts()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.active', 'DESC', 'p.modifiedAt')
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
