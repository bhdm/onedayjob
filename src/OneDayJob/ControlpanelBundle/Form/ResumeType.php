<?php

namespace OneDayJob\ControlpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResumeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('middle_name')
            ->add('birthdate')
            ->add('sex')
            ->add('relocate')
            ->add('citizenship')
            ->add('work_permit')
            ->add('phone')
            ->add('alternative_phone')
            ->add('email')
            ->add('skype')
            ->add('preferred_communication')
            ->add('created')
            ->add('vip')
            ->add('up')
            ->add('extra')
            ->add('term')
            ->add('salary')
            ->add('currency')
            ->add('specialty')
            ->add('city', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\City',
                'translation_property' => 'title',
            ])
            ->add('employee')
            ->add('languages', null, ['property' => 'title'])
            ->add('image')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Resume'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'onedayjob_apibundle_resume';
    }
}
