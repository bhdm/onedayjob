<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use OneDayJob\ApiBundle\Entity\SpecializationRepository;
use OneDayJob\ApiBundle\Entity\Country;
use OneDayJob\ApiBundle\Entity\Branch;

class VacancyType extends AbstractType 
{
	private $helper;

	public function __construct(\OneDayJob\FrontendBundle\Extensions\Helper $helper)
	{
		$this->helper = $helper;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('title')
            ->add('employment', 'choice', ['choices' => $this->helper->getEmployment()])
            ->add('work_experience', 'choice', ['choices' => $this->helper->getExperience()])
            ->add('education', 'choice', ['choices' => $this->helper->getEducation()])
            ->add('duty', 'textarea')
            ->add('skill', 'textarea')
            ->add('salary_per_month')
            ->add('salary_per_day')
            ->add('currency', 'choice', ['choices' => $this->helper->getCurrency()])
            ->add('extra', 'textarea', ['required' => false])
			->add('country', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\Country',
                'translation_property' => 'title',
            ])
            ->add('branch', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\Branch',
                'translation_property' => 'title',
            ]);

        $modifierBranch = function (FormInterface $form, Branch $branch = null) {
			$children = null === $branch ? array() : $branch->getChildren();

			$form->add('specialization', 'a2lix_translatedEntity', array(
				'class'    => 'OneDayJobApiBundle:Specialization',
				'translation_property' => 'title',
				'choices'  => $children
			));
		};

		$modifierCountry = function (FormInterface $form, Country $country = null) {
			$cities = null === $country ? array() : $country->getCities();

			$form->add('city', 'a2lix_translatedEntity', array(
				'class'    => 'OneDayJobApiBundle:City',
				'translation_property' => 'title',
				'choices'  => $cities
			));
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($modifierCountry, $modifierBranch) {
				$data = $event->getData();

				$modifierCountry($event->getForm(), $data->getCountry());
				$modifierBranch($event->getForm(), $data->getBranch());
			}
		);

		$builder->get('country')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($modifierCountry) {
				$country = $event->getForm()->getData();

				$modifierCountry($event->getForm()->getParent(), $country);
			}
		);

		$builder->get('branch')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($modifierBranch) {
				$branch = $event->getForm()->getData();

				$modifierBranch($event->getForm()->getParent(), $branch);
			}
		);

		/*$builder->add('title', 'text');
		$builder->add('employment', 'choice', ['choices' => $this->helper->getEmployment()]);
		$builder->add('work_schedule', 'choice', ['choices' => $this->helper->getSchedule()]);
		$builder->add('work_experience', 'choice', ['choices' => $this->helper->getExperience()]);
		$builder->add('education', 'choice', ['choices' => $this->helper->getEducation()]);
		$builder->add('salary', 'text');
		$builder->add('currency', 'choice', ['choices' => $this->helper->getCurrency()]);
		$builder->add('duty', 'textarea');
		$builder->add('skill', 'textarea');

		$builder->add('branch', 'entity', [
			'class' => 'OneDayJobApiBundle:Branch',
			'property' => 'title',
			'placeholder' => 'Выберите отрасль'
		]);

		$builder->add('country', 'entity', [
			'class' => 'OneDayJobApiBundle:Country', 
			'property' => 'title',
			'placeholder' => 'Выберите страну'
		]);

		$modifierBranch = function (FormInterface $form, Branch $branch = null) {
			$children = null === $branch ? array() : $branch->getChildren();

			$form->add('specialization', 'entity', array(
				'class'       => 'OneDayJobApiBundle:Specialization',
				'placeholder' => 'Выберите специализацию',
				'property' => 'title',
				'choices'     => $children
			));
		};

		$modifierCountry = function (FormInterface $form, Country $country = null) {
			$cities = null === $country ? array() : $country->getCities();

			$form->add('city', 'entity', array(
				'class'       => 'OneDayJobApiBundle:City',
				'placeholder' => 'Выберите город',
				'property' => 'title',
				'choices'     => $cities
			));
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($modifierCountry, $modifierBranch) {
				$data = $event->getData();

				$modifierCountry($event->getForm(), $data->getCountry());
				$modifierBranch($event->getForm(), $data->getBranch());
			}
		);

		$builder->get('country')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($modifierCountry) {
				$country = $event->getForm()->getData();

				$modifierCountry($event->getForm()->getParent(), $country);
			}
		);

		$builder->get('branch')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($modifierBranch) {
				$branch = $event->getForm()->getData();

				$modifierBranch($event->getForm()->getParent(), $branch);
			}
		);*/
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Vacancy',
        ));
    }

	public function getName()
	{
	   return 'fe_vacancy';
	}
}