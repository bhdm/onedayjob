<?php

namespace OneDayJob\ControlpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
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
            ->add('get_news_by_email')
            ->add('type')
            ->add('birthdate')
            ->add('balance')
            ->add('social_id')
            ->add('company')
            ->add('resume')
            ->add('image')
            ->add('favorite_vacancy')
            ->add('response_vacancy')
            ->add('favorite_company')
            ->add('favorite_resume')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'onedayjob_apibundle_user';
    }
}
