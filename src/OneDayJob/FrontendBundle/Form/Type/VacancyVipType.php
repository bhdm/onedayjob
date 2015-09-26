<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use OneDayJob\ApiBundle\Entity\VacancyRepository;
use OneDayJob\ApiBundle\Entity\Company;

class VacancyVipType extends AbstractType 
{
	private $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$company = $this->company;

		$builder->add('vacancy', 'entity', [
			'class' => 'OneDayJobApiBundle:Vacancy',
			'property' => 'title',
			'placeholder' => '',
			'query_builder' => function(VacancyRepository $er) use ($company) {
				return $er->createQueryBuilder('v')
					->where('v.company = :company')
					->setParameter('company', $company)
					->orderBy('v.id', 'ASC'); 
			}
		]);
	}

	public function getName()
	{
	   return 'fe_vacancy_vip';
	}
}