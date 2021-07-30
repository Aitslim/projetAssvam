<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findLastProjets(int $nb = 10)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active_status')
            ->setParameter('active_status', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    public function findOldProjets(int $nb = 10): array
    {
        $entityManager = $this->getEntityManager();

        // A REVOIR : propriétés de Project
        $query = $entityManager->createQuery(
            'SELECT p.id, p.title, p.createdAt, p.slug, p.imagefilename
            FROM App\Entity\Projet p
            WHERE p.active = :active_status
            ORDER BY p.createdAt ASC'
        )
            ->setParameter('active_status', true)
            ->setMaxResults($nb);

        return $query->getResult();
    }

    // A REVOIR : limit
    public function findAdminProjets(int $nb = 50)
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
     * @return Projet[] Returns an array of Projet objects
     */
    public function findProjetsByTitle(string $value)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
