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
    public function openResumeAction($id , $parameters = null)
    {
        $result = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Resume')->find($id);

        return $this->render('OneDayJobMainBundle:Resume:resume_open.html.twig', ['resume' => $result]);
    }

    public function similarResumeAction($id)
    {
        $resumes = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Resume')->getSimilarResumes($id);

        return $this->render('OneDayJobMainBundle:Resume:_resume.html.twig', ['resumes' => $resumes]);
    }
}