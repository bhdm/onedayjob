<?php

namespace OneDayJob\ApiBundle\Entity;


use Doctrine\ORM\EntityRepository;


class ResumeRepository extends EntityRepository
{
    public function getResumes(){

        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->orderBy('r.up', 'DESC')
            ->getQuery();
        return $result;
    }

    public function getSimilarResumes($id){

        $result= $this->createQueryBuilder('r')
            ->select('r')
            ->Where('r.id <> :id')
            ->setParameter('id' , $id)
            ->getQuery()
            ->getResult();

        return $result;
    }

}