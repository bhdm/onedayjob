<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\FeedbackType;
use OneDayJob\ApiBundle\Entity\Feedback;

class StaticController extends Controller
{
	public function aboutAction()
	{
		return $this->render('static_about.html.twig', ['title' => 'О компании']);
	}

	public function agreementAction()
	{
		return $this->render('static_agreement.html.twig', ['title' => 'Соглашение']);
	}

	public function feedbackAction(Request $request) 
	{
		$em = $this->getDoctrine()->getManager();

		$feedback = new Feedback();

		$form = $this->createForm(new FeedbackType(), $feedback);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em->persist($feedback);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', 'Ваше сообщение успешно отправлено. Спасибо.');
			
			return $this->redirect($this->generateUrl('fe_contact'));
		}

		$vars['title'] = 'Контакты';
		$vars['form'] = $form->createView();

		return $this->render('static_feedback.html.twig', $vars);
	}
}