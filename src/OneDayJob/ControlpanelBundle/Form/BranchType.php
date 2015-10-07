<?php

namespace OneDayJob\ControlpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BranchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', [
                'fields' => [
                    'title' => [
                        'label' => 'Заголовок',
                    ]
                ]
            ]);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OneDayJob\ApiBundle\Entity\Branch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'onedayjob_apibundle_branch';
    }
}
