<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class ProfileFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$year = date('Y');

		$builder
			->add('first_name', 'text')
			->add('last_name', 'text')
			->add('birthdate', 'date', ['years' => range($year - 60, $year - 15)]);
	}

	public function getParent() 
	{
		return 'fos_user_profile';
	}

	public function getName()
	{
	   return 'fe_user_profile';
	}
}