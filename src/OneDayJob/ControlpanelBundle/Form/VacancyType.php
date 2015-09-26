<?php

namespace OneDayJob\ControlpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VacancyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('employment')
            ->add('work_experience')
            ->add('education')
            ->add('duty')
            ->add('skill')
            ->add('salary_per_month')
            ->add('salary_per_day')
            ->add('currency')
            ->add('created')
            ->add('up')
            ->add('vip')
            ->add('urgent')
            ->add('views')
            ->add('extra')
            ->add('term')
            ->add('company', null, ['property' => 'name'])
            ->add('country')
            ->add('city')
            ->add('branch')
            ->add('specialization')
            ->add('response_employee')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Vacancy'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'onedayjob_apibundle_vacancy';
    }
}
