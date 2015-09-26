<?php

namespace OneDayJob\ControlpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('site')
            ->add('phone')
            ->add('description')
            ->add('city', 'a2lix_translatedEntity', [
                'class' => 'OneDayJob\ApiBundle\Entity\City',
                'translation_property' => 'title',
            ])
            ->add('image', 'file', ['required' => false, 'mapped' => false, ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Company'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'onedayjob_apibundle_company';
    }
}
