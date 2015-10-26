<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VacancyRepository extends EntityRepository
{
    public function VacancyWhere($SpecializationId , $VacancyID){
        $builder =$this->createQueryBuilder('v');
        $builder->where('v.specialization = ' . $SpecializationId);
        $builder->andWhere('v.id != ' . $VacancyID);
        return $builder;
    }

    public function SearchVacancy(){

    }

    public function getVacancies(){
        $builder =$this->createQueryBuilder('v')
            ->select('v, c, city')
            ->where('v.urgent >= :date_end')
            ->setParameter('date_end', new \DateTime())
            ->leftJoin('v.company', 'c')
            ->leftJoin('v.city', 'city')
            ->orderBy('v.up', 'DESC')
            ->getQuery();
        return $builder;
    }

    public function number_of_vacancies($company){
        $builder =$this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->Where('n.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
        return $builder;
    }

    public function branches_of_vacancies($company){
        $builder =$this->createQueryBuilder('v')
            ->select('v , b')
            ->Where('v.company = :company')
            ->setParameter('company', $company)
            ->leftJoin('v.branch', 'b')
            ->orderBy('v.branch', 'DESC')
            ->getQuery()
            ->getResult();
        return $builder;
    }

    public function findAll(){
        $this->findBy(['enabled' => true, 'status' => 1]);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria['enabled'] = true;
        $criteria['status'] = 1;
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }

    public function getSimilarVacancies($id){

        $result= $this->createQueryBuilder('r')
            ->select('r')
            ->Where('r.id <> :id')
            ->setParameter('id' , $id)
            ->getQuery()
            ->getResult();

        return $result;
    }
    
}
