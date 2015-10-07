<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;


class SpecializationRepository extends EntityRepository
{
    public function specializationsOrder($branch_id){
        $result = $this->createQueryBuilder('b')
            ->where('s.parent = :parent')
            ->setParameter('parent', $branch_id)
            ->orderBy('s.title')
            ->getQuery()
            ->getArrayResult();
        return $result;

    }

    public function specializations($branch_id){

        $result = $this->createQueryBuilder('b')
            ->where('s.parent = :parent')
            ->setParameter('parent', $branch_id)
            ->getQuery()
            ->getResult();
        return $result;

    }
}
