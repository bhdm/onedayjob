<?php

namespace OneDayJob\ApiBundle\Entity;


use Doctrine\ORM\EntityRepository;


class CompanyRepository extends EntityRepository
{
    public function leftJoinCompanyCityImage(){
        $result = $this->createQueryBuilder('c')
            ->select('c, s, i')
            ->leftJoin('c.city', 's')
            ->leftJoin('c.image', 'i')
            ->getQuery()
            ->getResult();
        return $result;
    }

    public function getSimilarCompanies($id){

        $result= $this->createQueryBuilder('c')
            ->select('c')
            ->Where('c.id <> :id')
            ->setParameter('id' , $id)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function filterCityCompany($city , $company){
        $builder = $this->createQueryBuilder('c');

        if ($company) {
            $builder->andWhere('c.id = :id');
            $builder->setParameter('id', $company);
        } else {
            if ($city) {
                $builder->andWhere('c.city = :city');
                $builder->setParameter('city', $city);
            }
        }
        $builder
            ->leftJoin('c.city', 's')
            ->leftJoin('c.image', 'i')
            ->getQuery()
            ->getResult();

        return $builder;
    }


    public function getCompanies($branch_id){

        $result = $this->createQueryBuilder('b')
            ->where('s.parent = :parent')
            ->setParameter('parent', $branch_id)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
        return $result;

    }



}