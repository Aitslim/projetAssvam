<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findCategoryPosts()
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('a')
            ->leftJoin('c.posts', 'a')
            // ->leftJoin('c.posts', 'a', 'WITH', 'a.active = 1')
            // ->addSelect('i')
            // ->leftJoin('c.posts', 'i', 'WITH', 'i.active = 0')
            ->getQuery();

        return $query->getResult();
    }

    // PAS BON
    public function newfindCategoryPosts(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c.id, c.name, sum(a.actif+i.inactif) as total, a.actif, i.inactif
                FROM App\Entity\Category c 
                LEFT JOIN (select category, count(*) as actif from App\Entity\Post where active = 1 GROUP BY category) a ON c.id = a.category 
                LEFT JOIN (select category, count(*) as inactif from App\Entity\Post where active = 0 GROUP BY category) i ON c.id = i.category 
                GROUP BY c.id
                order by 1'
        )
            ->setMaxResults(100);

        return $query->getResult();
    }


    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
