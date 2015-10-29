<?php

namespace OneDayJob\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\ResumeType;
use OneDayJob\FrontendBundle\Form\Type\SearchType;
use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\ApiBundle\Entity\Search;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ResumeController extends Controller
{

    /**
     * @Route("/resume/result", name="resume_result")
     * @Template()
     */

    public function searchAction(Request $request)
    {
        $city  			= filter_var($request->get('city', 0), FILTER_SANITIZE_NUMBER_INT);
        $branch 		= filter_var($request->get('branch', 0), FILTER_SANITIZE_NUMBER_INT);
        $text 			= $request->get('text');
        $currency 		= filter_var($request->get('currency', 'rub'), FILTER_SANITIZE_STRING);
        $salary 		= filter_var($request->get('salary', 0), FILTER_SANITIZE_NUMBER_INT);
        $term = $request->get('calendar');

        $arr_term = explode(" - " , $term);

        $term_from = $arr_term[0];
        $term_to = $arr_term[1];

        $em = $this->getDoctrine()->getManager();

        $builder =  $this->getDoctrine()->getRepository("OneDayJobApiBundle:Resume")->createQueryBuilder("r");
        if ($text) {
            $builder->Where("r.specialty  LIKE :text");
            $builder->setParameter('text', '%'.$text.'%');
        }
        if ($city && $city != -1) {
            $builder->andWhere('r.city = :city');
            $builder->setParameter('city', $city);
        }

        if ($city && $city != -1) {
            $builder->andWhere('r.city = :city');
            $builder->setParameter('city', $city);
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

            if($term_to && $flag){
                $builder->andWhere('r.termto < :termto');
                $builder->setParameter('termto', $term_to);
            }

        }

        if ($branch && $branch != -1) {
            $builder->andWhere('r.branch = :branch');
            $builder->setParameter('branch', $branch);
        }

//        $geo =$this->geoOrientation($this->getRequest());
//        $vars['local_country'] = $geo["country"];
//        $vars['local_country_id'] = $geo["id"];
//        $vars['countries'] 		 = $em->getRepository('OneDayJobApiBundle:Country')->findAll();
//        $vars['branches'] 		 = $em->getRepository('OneDayJobApiBundle:Branch')->findAll();
//        $vars = array();
        $resumes  = $builder->getQuery()->getResult();
//        $vars['title'] = 'Список резюме';

        return $this->render('OneDayJobMainBundle:Resume:resultResume.html.twig', ['resumes' => $resumes]);
    }


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



    public function getResumesAction(Request $request, $page, $ipp)
    {
        $query = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Resume')->getResumes();

        $resumes = $this->get('knp_paginator')->paginate($query, $page, $ipp);

        $_temp1 = [];
        $_temp2 = [];

        $session = $this->get('session');

        foreach ($resumes as $resume) {
            if ($session->has('locale')) {
                if ($resume->getCity()->translate()->getTitle() == $session->get('locale')['city']) {
                    $_temp1[] = $resume;
                } else {
                    $_temp2[] = $resume;
                }
            } else {
                $_temp1[] = $resume;
            }
        }

        $resumes->setItems(array_merge($_temp1, $_temp2));

        return $this->render('OneDayJobMainBundle:Resume:_resume.html.twig', ['resumes' => $resumes]);
    }

    /**
     * @Route("/resume/show/{id}/{parameters}", name="resume_show")
     * @Template()
     */
    public function openResumeAction(Request $request , $id , $parameters = null)
    {
        $result = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Resume')->find($id);
        $referer = $request->headers->get('referer');

        return $this->render('OneDayJobMainBundle:Resume:resume_open.html.twig', ['resume' => $result , 'referer' => $referer]);
    }

    public function similarResumeAction($id)
    {
        $resumes = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Resume')->getSimilarResumes($id);

        return $this->render('OneDayJobMainBundle:Resume:_resume.html.twig', ['resumes' => $resumes]);
    }
}