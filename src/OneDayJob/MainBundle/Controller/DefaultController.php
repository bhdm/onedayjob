<?php

namespace OneDayJob\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 * @package OneDayJob\MainBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/" , name="mainpage")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('OneDayJobMainBundle:Default:index.html.twig');
    }

    /**
     * @Route("/registration" , name="registration")
     * @Template()
     */
    public function registrationAction()
    {
        return $this->render('OneDayJobMainBundle:Default:registration.html.twig');
    }

//    /**
//     * parameters содержит серриализованный массив фильтров
//     * @Route("/vacancy/show/{id}/{parameters}", name="vacancy_chow")
//     * @Template("")
//     */
//    public function showVacancyAction($id, $parameters = null){
//        $vacancy = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy')->findOne(['id' => $id, 'enabled' => true]);
//        if (!$vacancy){
//            return $this->createAccessDeniedException('Данной вакансии не существует');
//        }
//        return ['vacancy' => $vacancy];
//    }


    /**
     * @Route("/page/{slug}" , name="page")
     * @Template()
     */
    public function pageAction($slug){
        $page = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Page')->findOneBy($slug);
        return ['page' => $page];
    }


}
