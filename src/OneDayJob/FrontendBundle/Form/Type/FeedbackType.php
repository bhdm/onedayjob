<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FeedbackType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('first_name', 'text')
			->add('email', 'email')
			->add('message', 'textarea');
	}

	public function getName()
	{
	   return 'fe_feedback';
	}
}