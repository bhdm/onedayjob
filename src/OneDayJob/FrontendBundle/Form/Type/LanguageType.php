<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LanguageType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// $builder->add('language', 'entity', [
		// 	'class' => 'OneDayJobApiBundle:Vacancy',
		// 	'property' => 'title',
		// 	'placeholder' => 'Выберите вакансию',
		// 	'query_builder' => function(VacancyRepository $er) use ($company) {
		// 		return $er->createQueryBuilder('v')
		// 			->where('v.company = :company')
		// 			->setParameter('company', $company)
		// 			->orderBy('v.id', 'ASC'); 
		// 	}
		// ]);
	}

	public function getName()
	{
	   return 'fe_language';
	}
}