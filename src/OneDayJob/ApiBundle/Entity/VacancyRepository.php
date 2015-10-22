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
    
}
