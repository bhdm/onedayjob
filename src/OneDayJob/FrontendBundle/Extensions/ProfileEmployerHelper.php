<?php
namespace OneDayJob\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class ProfileEmployerHelper
{
	private $em;
	private $sc;

	function __construct(EntityManager $em, $security_context)
	{
		$this->em = $em;
		$this->sc = $security_context;
	}

	public function getVacancyCount()
	{
		$company = $this->sc->getToken()->getUser()->getCompany();

		$builder = $this->em->createQueryBuilder();
		$builder->select('COUNT(v.id)');
		$builder->from('OneDayJobApiBundle:Vacancy', 'v');
		$builder->where('v.company = :company');
		$builder->setParameter('company', $company);

		$count = $builder->getQuery()->getSingleScalarResult();

		return $count;
	}

	public function getFavoriteCount()
	{
		$favorite = $this->sc->getToken()->getUser()->getFavoriteVacancy();
		$count = $favorite->count();

		return $count;
	}

	public function getResponseCount()
	{
		return $this->sc->getToken()->getUser()->getResponseVacancy()->count();
	}

	public function getFavoriteVacancies()
	{
		$vacancies = $this->sc->getToken()->getUser()->getFavoriteVacancy();

		return $vacancies->slice(0, 3);
	}


	public function getFavoriteResumeCount()
	{
		return $this->sc->getToken()->getUser()->getFavoriteResume()->count();
	}

	public function getEmployerResponseCount()
	{
		$user = $this->sc->getToken()->getUser();

		$count = 0;

		if ($company = $user->getCompany()) {
			foreach ($company->getVacancies() as $vacancy) {
				if (!$vacancy->getResponseEmployee()->isEmpty()) {
					$count += 1;
				}
			}
		}

		return $count;
	}
}