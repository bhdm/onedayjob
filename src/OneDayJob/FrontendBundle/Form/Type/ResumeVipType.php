<?php

namespace OneDayJob\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use OneDayJob\ApiBundle\Entity\User;

class ResumeVipType extends AbstractType 
{
	private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$user = $this->user;

		$builder->add('resume', 'entity', [
			'class' => 'OneDayJobApiBundle:Resume',
			'property' => 'getFullName',
			'query_builder' => function(EntityRepository $er) use ($user) {
				return $er->createQueryBuilder('r')
					->where('r.employee = :employee')
					->setParameter('employee', $user->getId()); 
			}
		]);
	}

	public function getName()
	{
	   return 'fe_resume_vip';
	}
}