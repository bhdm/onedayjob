<?php

namespace OneDayJob\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\VacancyType;
use OneDayJob\ApiBundle\Entity\Vacancy;
use OneDayJob\ApiBundle\Entity\Search;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VacancyController extends Controller
{
	# Vacancy

	public function getUrgentVacanciesAction(Request $request, $page, $ipp)
	{
        $query = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy')->getVacancies();

		$vacancies = $this->get('knp_paginator')->paginate($query, $page, $ipp);

        $_temp1 = [];
		$_temp2 = [];

		$session = $this->get('session');

		foreach ($vacancies as $vacancy) {
			if ($session->has('locale')) {
				if ($vacancy->getCity()->translate()->getTitle() == $session->get('locale')['city']) {
	    			$_temp1[] = $vacancy;
	    		} else {
	    			$_temp2[] = $vacancy;
	    		}
			} else {
				$_temp1[] = $vacancy;
			}
    	}

    	$vacancies->setItems(array_merge($_temp1, $_temp2));

		return $this->render('OneDayJobMainBundle:Vacancy:_vacancy.html.twig', ['vacancies' => $vacancies]);
    }

    /**
     * parameters содержит серриализованный массив фильтров
     * @Route("/vacancy/show/{id}/{parameters}", name="vacancy_chow")
     * @Template("")
     */
    public function showVacancyAction(Request $request, $id, $parameters = null){
        $vacancy = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy')->find($id);
        if (!$vacancy){
//            return $this->createAccessDeniedException('Данной вакансии не существует');
        }
        $referer = $request->headers->get('referer');
        return $this->render('OneDayJobMainBundle:Vacancy:vacancy_show.html.twig', ['vacancy' => $vacancy , 'referer' => $referer]);
    }

    public function similarVacancyAction($id)
    {
        $vacancies = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy')->getSimilarVacancies($id);

        return $this->render('OneDayJobMainBundle:Vacancy:_vacancy.html.twig', ['vacancies' => $vacancies]);
    }

//	public function indexAction(Request $request)
//	{
//		$specialization = filter_var($request->query->get('specialization', 0), FILTER_SANITIZE_NUMBER_INT);
//		$branch 		= filter_var($request->query->get('branch', 0), FILTER_SANITIZE_NUMBER_INT);
//		$text 			= filter_var($request->query->get('text'), FILTER_SANITIZE_STRING);
//		$currency 		= filter_var($request->query->get('currency', 'rub'), FILTER_SANITIZE_STRING);
//		$country 	    = filter_var($request->query->get('country', 0), FILTER_SANITIZE_NUMBER_INT);
//		$experience 	= filter_var($request->query->get('experience', 0), FILTER_SANITIZE_NUMBER_INT);
//		$salary 		= filter_var($request->query->get('salary', 0), FILTER_SANITIZE_NUMBER_INT);
//		$education 		= filter_var($request->query->get('education', 0), FILTER_SANITIZE_NUMBER_INT);
//		$employment 	= filter_var($request->query->get('employment', 0), FILTER_SANITIZE_NUMBER_INT);
//        $term_from      = $request->get('term_from');
//        $term_to       	= $request->get('term_to');
//
////		$fm = $this->get('padam87_search.filter.manager');
////		$filter = new \Padam87\SearchBundle\Filter\Filter(['title' => '*' . $text . '*'], 'OneDayJobApiBundle:Vacancy', 'v');
////		$builder = $fm->createQueryBuilder($filter);
////		$builder->select('v, c, city');
//
//
//        $em = $this->getDoctrine()->getManager();
//
//        $builder = $em->createQueryBuilder('v');
//        $builder->select('v');
//        $builder->from('OneDayJobApiBundle:Vacancy','v');
//
//        /**
//         * @TODO здесь нужно поменять term но здесь padam87_search делать как в Resume?
//         */
//		if ($salary) {
//			$builder->andWhere('v.currency = :currency');
//			$builder->setParameter('currency', $currency);
//
//			if ($salary <= 100000) {
//				$builder->andWhere('v.salary_per_month <= :salary');
//				$builder->setParameter('salary', $salary);
//			}
//		}
//
//		if ($experience) {
//			$builder->andWhere('v.work_experience <= :work_experience');
//			$builder->setParameter('work_experience', $experience);
//		}
//
//        if ($term_from || $term_to) {
//            // This check needs when we have term_from and term_to it means that term_to must be more than term_from!
//            $flag = true;
//            if(($term_from >= $term_to) && ($term_from && $term_to)){
//                $flag = false;
//            }
//
//
//            if($term_from && $flag){
//                $builder->andWhere('v.termfrom > :termfrom');
//                $builder->setParameter('termfrom', $term_from);
//            }
//
//            if($term_from && $flag){
//                $builder->andWhere('v.termto < :termto');
//                $builder->setParameter('termfrom', $term_from);
//            }
//
//        }
//
//		if ($employment) {
//			$builder->andWhere('v.employment = :employment');
//			$builder->setParameter('employment', $employment);
//		}
//
//		if ($country && $country != -1) {
//			$builder->andWhere('v.country = :country');
//			$builder->setParameter('country', $country);
//		}
//
//		if ($education) {
//			$builder->andWhere('v.education = :education');
//			$builder->setParameter('education', $education);
//		}
//
//		if ($branch) {
//			$builder->andWhere('v.branch = :branch');
//			$builder->setParameter('branch', $branch);
//		}
//
//		$builder->leftJoin('v.company', 'c');
//		$builder->leftJoin('v.city', 'city');
//		$builder->orderBy('v.up', 'DESC');
//
//		$em = $this->getDoctrine()->getEntityManager();
//
//        $geo =$this->geoOrientation($this->getRequest());
//        $vars['local_country'] = $geo["country"];
//        $vars['local_country_id'] = $geo["id"];
//		$vars['countries'] 		 = $em->getRepository('OneDayJobApiBundle:Country')->findAll();
//		$vars['specializations'] = $em->getRepository('OneDayJobApiBundle:Specialization')->findAll();
//		$vars['branches'] 		 = $em->getRepository('OneDayJobApiBundle:Branch')->findAll();
//
//		$vars['vacancies']		 = $builder->getQuery()->getResult();
//
//		return $this->render('OneDayJobFrontendBundle:Vacancy:vacancy_index.html.twig', $vars);
//	}
//
//	public function vacancyAction(Vacancy $vacancy)
//	{
//        $geo =$this->geoOrientation($this->getRequest());
//        $vars['local_country'] = $geo["country"];
//        $vars['local_country_id'] = $geo["id"];
//		$repository = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy');
//		$builder = $repository->createQueryBuilder('v');
//		$builder->where('v.specialization = ' . $vacancy->getSpecialization()->getId());
//		$builder->andWhere('v.id != ' . $vacancy->getId());
//
//		$vars['similar_vacancies'] = $builder->getQuery()->getResult();
//		$vars['vacancy'] = $vacancy;
//		$vars['cities'] = $this->getDoctrine()->getRepository('OneDayJobApiBundle:City')->findAll();
//		$vars['title'] = $vacancy->getTitle();
//
//		return $this->render('OneDayJobFrontendBundle:Default:vacancy.html.twig', $vars);
//	}

//    protected function geoOrientation($request)
//    {
//        $locale =  $request->get('_locale');
//        $user_ip = $_SERVER["REMOTE_ADDR"];
//        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip='$user_ip'"));
//        $country = $geo["geoplugin_countryName"];
//
//        $repository = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->findBy(array("title" => $country));
//
//
//        $country_id = ceil($repository[0]->getId() / 3 );
//
//        if($locale == "ru")
//            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() - 1)->getTitle();
//        elseif($locale == "de")
//            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() + 1)->getTitle();
//
//        return array(
//            "id" => $country_id,
//            "country" => $country
//        );
//    }
}