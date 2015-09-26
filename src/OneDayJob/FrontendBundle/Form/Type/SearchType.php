<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use OneDayJob\ApiBundle\Entity\Branch;

class SearchType extends AbstractType
{
    private $helper;

	public function __construct(\OneDayJob\FrontendBundle\Extensions\Helper $helper)
	{
		$this->helper = $helper;	
	}
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\City',
                'translation_property' => 'title',
            ])
            ->add('text', null, ['attr' => ['placeholder' => '']])
            ->add('branch', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\Branch',
                'translation_property' => 'title',
            ])
            ->add('work_experience', 'hidden')
            ->add('salary_from', 'hidden')
            ->add('education', 'choice', ['choices' => $this->helper->getEducation()])
            ->add('employment', 'choice', ['choices' => $this->helper->getEmployment()])
            ->add('currency', 'choice', ['choices' => $this->helper->getCurrency()])
        ;

        $modifierBranch = function (FormInterface $form, Branch $branch = null) {
			$children = null === $branch ? array() : $branch->getChildren();

			$form->add('specialization', 'a2lix_translatedEntity', array(
				'class'       => 'OneDayJobApiBundle:Specialization',
				'placeholder' => '',
				'translation_property' => 'title',
				'choices'     => $children
			));
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($modifierBranch) {
				$data = $event->getData();

				$modifierBranch($event->getForm(), $data->getBranch());
			}
		);

		$builder->get('branch')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($modifierBranch) {
				$branch = $event->getForm()->getData();

				$modifierBranch($event->getForm()->getParent(), $branch);
			}
		);
    }
    
    public function getName()
    {
        return 'search';
    }
}
