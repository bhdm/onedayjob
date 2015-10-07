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
        $country 	    = filter_var($request->query->get('country', 0), FILTER_SANITIZE_NUMBER_INT);
		$branch 		= filter_var($request->get('branch', 0), FILTER_SANITIZE_NUMBER_INT);
		$text 			= filter_var($request->get('text'), FILTER_SANITIZE_STRING);
		$currency 		= filter_var($request->get('currency', 'rub'), FILTER_SANITIZE_STRING);
		$salary 		= filter_var($request->get('salary', 0), FILTER_SANITIZE_NUMBER_INT);
		$term_from      = $request->get('term_from');
		$term_to       	= $request->get('term_to');

		$em = $this->getDoctrine()->getManager();

		$builder = $em->createQueryBuilder('r');
		$builder->select('r');
		$builder->from('OneDayJobApiBundle:Resume','r');
        if ($text) {
            $builder->andWhere("r.specialty  LIKE '%:text%'");
            $builder->setParameter('text', $text);
        }
		if ($city && $city != -1) {
			$builder->andWhere('r.city = :city');
			$builder->setParameter('city', $city);
		}

        if ($city && $city != -1) {
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


		if ($term_from || $term_to) {
            // This check needs when we have term_from and term_to it means that term_to must be more than term_from!
            $flag = true;
            if(($term_from >= $term_to) && ($term_from && $term_to)){
                $flag = false;
            }


            if($term_from && $flag){
                $builder->andWhere('r.termfrom > :termfrom');
                $builder->setParameter('termfrom', $term_from);
            }

            if($term_from && $flag){
                $builder->andWhere('r.termto < :termto');
                $builder->setParameter('termfrom', $term_from);
            }

        }

		if ($branch && $branch != -1) {
			$builder->andWhere('r.branch = :branch');
			$builder->setParameter('branch', $branch);
		}

        $geo =$this->geoOrientation($this->getRequest());
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];
		$vars['countries'] 		 = $em->getRepository('OneDayJobApiBundle:Country')->findAll();
		$vars['branches'] 		 = $em->getRepository('OneDayJobApiBundle:Branch')->findAll();
		$vars['resumes']         = $builder->getQuery()->getResult();
		$vars['title'] = 'Список резюме';

		return $this->render('OneDayJobFrontendBundle:Resume:resume_index.html.twig', $vars);
	}

	public function resumeAction(Resume $resume)//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	{
//		$vars['resume'] = $resume;
//		$vars['title'] = $resume->getFullName();
//
//		return $this->render('OneDayJobFrontendBundle:Default:resume.html.twig', $vars);
	}

    protected function geoOrientation($request)
    {
        $locale =  $request->get('_locale');
        $user_ip = $_SERVER["REMOTE_ADDR"];
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip='$user_ip'"));
        $country = $geo["geoplugin_countryName"];

        $repository = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->findBy(array("title" => $country));


        $country_id = ceil($repository[0]->getId() / 3 );

        if($locale == "ru")
            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() - 1)->getTitle();
        elseif($locale == "de")
            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() + 1)->getTitle();

        return array(
            "id" => $country_id,
            "country" => $country
        );
    }
}