<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\ResumeType;
use OneDayJob\FrontendBundle\Form\Type\SearchType;
use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\ApiBundle\Entity\Search;


class ResumeController extends Controller
{
	public function indexAction(Request $request)
	{
		$sex   			= filter_var($request->get('sex', 0), FILTER_SANITIZE_STRING);
		$city  			= filter_var($request->get('city', 0), FILTER_SANITIZE_NUMBER_INT);
		$specialization = filter_var($request->get('specialization', 0), FILTER_SANITIZE_NUMBER_INT);
		$branch 		= filter_var($request->get('branch', 0), FILTER_SANITIZE_NUMBER_INT);
		$text 			= filter_var($request->get('text'), FILTER_SANITIZE_STRING);
		$currency 		= filter_var($request->get('currency', 'rub'), FILTER_SANITIZE_STRING);
		$salary 		= filter_var($request->get('salary', 0), FILTER_SANITIZE_NUMBER_INT);
		$term       	= filter_var($request->get('term', 0), FILTER_SANITIZE_NUMBER_INT);

		$em = $this->getDoctrine()->getManager();

		$fm = $this->get('padam87_search.filter.manager');
		$filter = new \Padam87\SearchBundle\Filter\Filter(['specialty' => '*' . $text . '*'], 'OneDayJobApiBundle:Resume', 'r');
		$builder = $fm->createQueryBuilder($filter);
		$builder->select('r');

		if ($city) {
			$builder->andWhere('r.city = :city');
			$builder->setParameter('city', $city);
		}

		if ($sex) {
			$builder->andWhere('r.sex = :sex');
			$builder->setParameter('sex', $sex);
		}

		if ($salary) {
			$builder->andWhere('r.currency = :currency');
			$builder->setParameter('currency', $currency);

			$builder->andWhere('r.salary <= :salary');
			$builder->setParameter('salary', $salary);
		}

		if ($term) {
			$builder->andWhere('r.term <= :term');
			$builder->setParameter('term', $term);
		}

		if ($branch) {
			$builder->andWhere('r.branch = :branch');
			$builder->setParameter('branch', $branch);
		}

		$vars['countries'] 		 = $em->getRepository('OneDayJobApiBundle:Country')->findAll();
		$vars['branches'] 		 = $em->getRepository('OneDayJobApiBundle:Branch')->findAll();
		$vars['resumes']         = $builder->getQuery()->getResult();
		$vars['title'] = 'Список резюме';

		return $this->render('resume_index.html.twig', $vars);
	}

	public function resumeAction(Resume $resume)
	{
		$vars['resume'] = $resume;
		$vars['title'] = $resume->getFullName();

		return $this->render('OneDayJobFrontendBundle:Default:resume.html.twig', $vars);
	}
}