<?php
namespace App\Repository;

use App\Entity\Competition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 *
 * @method Competition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competition[] findAll()
 * @method Competition[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    
    /**
     *
     * @return Competition[] Returns an array of Competition objects
     */
    public function findByStatus($value)

    {
        return $this->createQueryBuilder('competition')
            ->andWhere('competition.statusId = :val')
            ->setParameter('val', $value)
            ->orderBy('competition.creationDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    

    
    public function findOneBySomeField($value): ?Competition
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    


    /*
     * public function findOneBySomeField($value): ?Competition
     * {
     * return $this->createQueryBuilder('c')
     * ->andWhere('c.exampleField = :val')
     * ->setParameter('val', $value)
     * ->getQuery()
     * ->getOneOrNullResult()
     * ;
     * }
     */

}
