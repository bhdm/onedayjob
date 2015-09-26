<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use OneDayJob\ApiBundle\Entity\Country;

class CompanyType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', 'text')
			->add('phone', 'text')
			->add('site', 'text')
			->add('description', 'textarea')
			->add('city', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\City',
                'translation_property' => 'title',
            ]);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'OneDayJob\ApiBundle\Entity\Company',
		));
	}

	public function getName()
	{
	   return 'fe_company_edit';
	}
}