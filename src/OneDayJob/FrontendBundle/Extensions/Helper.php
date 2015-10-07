<?php
namespace OneDayJob\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class Helper
{
	private $em;

	private $container;

	private $employment = [
		1 => 'helper.employment.employment_1',
		2 => 'helper.employment.employment_2',
		3 => 'helper.employment.employment_3',
		4 => 'helper.employment.employment_4',
		5 => 'helper.employment.employment_5'
	];

	private $experience = [
		1 => 'helper.experience.experience_1',
		2 => 'helper.experience.experience_2',
		3 => 'helper.experience.experience_3',
		4 => 'helper.experience.experience_4'
	];

	private $currency = [
		'rub' => 'RUB',
		'usd' => 'USD',
		'eur' => 'EUR'
	];

	private $term = [
		 5 => 5,
		10 => 10,
		15 => 15,
		20 => 20,
		25 => 25,
		30 => 30
	];

	private $education = [
		1 => 'helper.education.education_1',
		2 => 'helper.education.education_2',
		3 => 'helper.education.education_3',
		4 => 'helper.education.education_4'
	];

	private $schedule = [
		1 => 'helper.schedule.schedule_1',
		2 => 'helper.schedule.schedule_2',
		3 => 'helper.schedule.schedule_3',
		4 => 'helper.schedule.schedule_4',
		5 => 'helper.schedule.schedule_5'
	];

	private $locales = [
		'ru' => 'Русский',
		'en' => 'English',
		'de' => 'Deutsch'
	];

	public function __construct(EntityManager $em, $container)
	{
		$this->em = $em;
		$this->container = $container;
	}

	public function getDate(\DateTime $date)
	{
		$month = $this->container->get('translator')->trans('months.' . intval($date->format('m')));

		return $date->format('d') . ' ' . $month . ' ' . $date->format('Y');
	}

	public function getLocales($locale = null)
	{
		if ($locale) {
			return $this->locales[$locale];
		}

		return $this->locales;
	}

	public function getNewResponses(\OneDayJob\ApiBundle\Entity\User $employer)
	{
		return $this->em
            ->getRepository('OneDayJobApiBundle:Vacancy')
            ->createQueryBuilder('v')
            ->select('SUM(v.responses)')
            ->where('v.company = :company')
            ->setParameter('company', $employer->getCompany())
            ->getQuery()
            ->getSingleScalarResult();
	}

	public function getSearchFormData()
	{
		$vars['countries'] = $this->em
			->getRepository('OneDayJobApiBundle:Country')
			->createQueryBuilder('c')
			->select('c, t')
            ->join('c.translations', 't')
			->orderBy('t.title')
			->getQuery()
			->getResult();

		$vars['cities'] = $this->em
			->getRepository('OneDayJobApiBundle:City')
			->createQueryBuilder('c')
			->where('c.country = :country')
			->setParameter('country', $vars['countries'][0]->getId())
			->select('c, t')
            ->join('c.translations', 't')
			->orderBy('t.title')
			->getQuery()
			->getResult();

		$vars['branches'] = $this->em
			->getRepository('OneDayJobApiBundle:Branch')
			->createQueryBuilder('b')
			->select('b, t')
            ->join('b.translations', 't')
			->orderBy('t.title')
			->getQuery()
			->getResult();

		$vars['companies'] = $this->em
			->getRepository('OneDayJobApiBundle:Company')
			->createQueryBuilder('c')
			->orderBy('c.name')
			->getQuery()
			->getResult();

		$vars['currency'] = $this->currency;
		$vars['term'] = $this->term;

		return $vars;
	}

	public function getCities($country_id)
	{
		return $this->em
			->getRepository('OneDayJobApiBundle:City')
			->createQueryBuilder('c')
			->where('c.country = :country')
			->setParameter('country', $country_id)
			#->orderBy('c.title')
			->getQuery()
			->getResult();
	}

	public function getEmployment($key = null)
	{
		$translator = $this->container->get('translator');
		$translator->setLocale($this->container->get('session')->get('__locale'));

		if (isset($this->employment[$key])) {
			return $translator->trans($this->employment[$key]);
		}

		return array_map(function($item) use($translator){
				return $translator->trans($item);
			}, $this->employment);
	}

	public function getExperience($key = null)
	{
		$translator = $this->container->get('translator');
		$translator->setLocale($this->container->get('session')->get('__locale'));

		if (isset($this->experience[$key])) {
			return $translator->trans($this->experience[$key]);
		}

		return array_map(function($item) use($translator){
				return $translator->trans($item);
			}, $this->experience);
	}

	public function getCurrency($key = null)
	{
		if (isset($this->currency[$key])) {
			return $this->currency[$key];
		}

		return $this->currency;
	}

	public function getEducation($key = null)
	{
		$translator = $this->container->get('translator');

		if (isset($this->education[$key])) {
			return $translator->trans($this->education[$key]);
		}

		return array_map(function($item) use($translator){
				return $translator->trans($item);
			}, $this->education);
	}

	public function getSchedule($key = null)
	{
		$translator = $this->container->get('translator');

		if (isset($this->schedule[$key])) {
			return $translator->trans($this->schedule[$key]);
		}

		return array_map(function($item) use($translator){
				return $translator->trans($item);
			}, $this->schedule);
	}
}