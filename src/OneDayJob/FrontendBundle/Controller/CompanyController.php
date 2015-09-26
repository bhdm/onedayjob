<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\CompanyType;
use OneDayJob\ApiBundle\Entity\Company;

class CompanyController extends Controller
{
	# Company

	public function get_companiesAction()
	{
		$companies = $this->getDoctrine()
			->getRepository('OneDayJobApiBundle:Company')
			->createQueryBuilder('c')
			->select('c, s, i')
			//->select('c.id, c.name, c.site, c.phone, c.description, s.title AS city, i.file_name as image')
			/*->addSelect('
				(SELECT COUNT(v.id)
                 FROM OneDayJobApiBundle:Vacancy v
                 WHERE v.company = c.id) AS vacancy_quantity
			')*/
			->leftJoin('c.city', 's')
			->leftJoin('c.image', 'i')
			->getQuery()
			->getResult();

		$_temp1 = [];
		$_temp2 = [];

		$session = $this->get('session');

		foreach ($companies as $company) {
			if ($session->has('locale')) {
				if ($company->getCity()->translate()->getTitle() == $session->get('locale')['city']) {
	    			$_temp1[] = $company;
	    		} else {
	    			$_temp2[] = $company;
	    		}
			} else {
				$_temp1[] = $company;
			}
    	}

		return $this->render('_company.html.twig', ['companies' => array_merge($_temp1, $_temp2)]);
	}

	public function indexAction(Request $request)
	{
		$city    = filter_var($request->query->get('city', 0), FILTER_SANITIZE_NUMBER_INT);
		$company = filter_var($request->query->get('company_id', 0), FILTER_SANITIZE_NUMBER_INT);

		$repository = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Company');
		$builder = $repository->createQueryBuilder('c');

		

		if ($company) {
			$builder->andWhere('c.id = :id');
			$builder->setParameter('id', $company);
		} else {
			if ($city) {
				$builder->andWhere('c.city = :city');
				$builder->setParameter('city', $city);
			}
		}

		$builder
			->leftJoin('c.city', 's')
			->leftJoin('c.image', 'i');

		
		$vars['cities'] = $this->getDoctrine()->getRepository('OneDayJobApiBundle:City')->findBy([]);
		$vars['companies'] = $builder->getQuery()->getResult();
		$vars['title'] = 'Компании';

		return $this->render('company_index.html.twig', $vars);
	}

	public function companyAction(Company $company)
	{
		$vacancies = [];

		foreach ($company->getVacancies() as $vacancy) {
			$vacancies[$vacancy->getBranch()->translate()->getTitle()][] = $vacancy;
		}

		ksort($vacancies);

		$vars['company_index'] = $company;
		$vars['company_vacancies'] = $vacancies;
		//$vars['title'] = $company->getName();
		return $this->render('company.html.twig', $vars);
	}
}