<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class RegistrationFormType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			//->add('username', 'text')
			->add('first_name', 'text')
			->add('last_name', 'text')
			->add('type', 'hidden', ['attr' => ['class' => 'user_type', 'value' => 'employee']])
			->add('social_id', 'hidden')
			->add('get_news_by_email', 'checkbox', ['required' => false]);
	}

	public function getParent() 
	{
		return 'fos_user_registration';
	}

	public function getName()
	{
	   return 'fe_user_registration';
	}
}