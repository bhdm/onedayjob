<?php

namespace OneDayJob\ApiBundle\Entity;


use Doctrine\ORM\EntityRepository;


class CityRepository extends EntityRepository
{
    public function countryCitiesOrder($country_id){

        $result = $this->createQueryBuilder('b')
            ->where('c.country = :country')
            ->setParameter('country', $country_id)
            ->orderBy('c.title')
            ->getQuery()
            ->getArrayResult();
        return $result;

    }

    public function countryCities($country_id){

        $result = $this->createQueryBuilder('b')
            ->where('c.country = :country')
            ->setParameter('country', $country_id)
            ->getQuery()
            ->getResult();
        return $result;

    }

}