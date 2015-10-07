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
    
}
