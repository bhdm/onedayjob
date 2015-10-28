<?php

namespace OneDayJob\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $year = date('Y');

        $builder
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('birthdate', 'date', ['years' => range($year - 60, $year - 15)])
            ->add('email' , 'text')
            ->add('password' , 'fos_user_change_password');
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'fe_user_profile';
    }
}