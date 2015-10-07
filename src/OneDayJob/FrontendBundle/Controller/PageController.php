<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
	public function indexAction($slug)
	{
		$repositiry = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Page');
		$page = $repositiry->findOneBySlug($slug);

		$vars['page'] = $page;
		$vars['title'] = $page->getTitle();

        /**
         * @TODO СДЕЛАТЬ twig для page контроллера
         */
		return $this->render('OneDayJobFrontendBundle:Default:page_index.html.twig', $vars);
	}
}