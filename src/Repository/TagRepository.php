<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findByLabelLike(string $pattern)
    {
       // SELECT t.* FROM Tag as t WHERE t.label LIKE '%pattern%'
       $queryBuilder = $this->createQueryBuilder('t');
       $queryBuilder->Where(
           $queryBuilder->expr()->like('t.label', ':pattern')
           );
       
       $queryBuilder->setParameter('pattern', '%'.$pattern.'%');
       
       return $queryBuilder->getQuery()->getResult();
    }

}