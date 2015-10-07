<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use OneDayJob\ApiBundle\Entity\Country;

class ResumeType extends AbstractType 
{
	private $helper;

	public function __construct(\OneDayJob\FrontendBundle\Extensions\Helper $helper)
	{
		$this->helper = $helper;	
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('first_name')
			->add('last_name')
			->add('middle_name')
			->add('birthdate', 'date', [
				'years' => range(date('Y') - 15, date('Y') -50)
			])
			->add('termfrom' , 'date' )
			->add('termto' , 'date')
			->add('salary')
			->add('currency', 'choice', ['choices' => $this->helper->getCurrency()])
			->add('branch', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\Branch',
                'translation_property' => 'title',
            ])
			->add('specialty')
			->add('sex', 'choice', [
				'multiple' => false,
				'expanded' => true,
				'choices' => ['male' => 'Мужской', 'female' => 'Женский']
			])
			->add('education', 'collection', ['type' => new EducationType(), 'allow_add' => true, 'required' => false])
 			->add('experience', 'collection', ['type' => new ExperienceType(), 'allow_add' => true, 'required' => false])
			->add('city', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\City',
                'translation_property' => 'title',
            ])
			->add('relocate', 'choice', [
				'multiple' => false,
				'expanded' => true,
				'choices' => [1 => 'Готов', 0 => 'Никогда']
			])
			->add('citizenship', 'choice', [
				'multiple' => false,
				'expanded' => true,
				'choices' => [1 => 'Готов', 0 => 'Другое']
			])
			->add('work_permit', 'choice', [
				'multiple' => false,
				'expanded' => true,
				'choices' => [1 => 'Готов', 0 => 'Другое']
			])
			->add('alternative_phone')
			->add('phone')
			->add('email')
			->add('skype')
			->add('preferred_communication', 'choice', [
				'multiple' => false,
				'expanded' => true,
				'choices' => ['phone' => '', 'skype' => '']
			])
            ->add('imageId','hidden',['mapped' => false, 'attr' => ['class'=> 'image-id']])
			->add('extra', 'textarea')
			//->add('image', 'file', ['required' => false])
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Resume',
        ));
    }

	public function getName()
	{
	   return 'fe_resume';
	}
}