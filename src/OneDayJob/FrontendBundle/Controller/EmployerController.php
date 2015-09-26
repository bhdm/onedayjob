<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\CompanyType;
use OneDayJob\ApiBundle\Entity\Company;
use OneDayJob\FrontendBundle\Form\Type\VacancyType;
use OneDayJob\FrontendBundle\Form\Type\VacancyUpType;
use OneDayJob\FrontendBundle\Form\Type\VacancyVipType;
use OneDayJob\FrontendBundle\Form\Type\VacancyUrgentType;
use OneDayJob\ApiBundle\Entity\Vacancy;

class EmployerController extends Controller
{
	# Company

	public function companyAction(Request $request)
	{
		$translator = $this->get('translator');
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			$company = new Company();
		}

		// Сделать проверку на то, чтобы вакансия принадлежала пользователю.

		$form = $this->createForm(new CompanyType(), $company);
		$form->handleRequest($request);

		if ($form->isValid()) {

			$employer->setCompany($company);

			$em = $this->getDoctrine()->getManager();
			$em->persist($company);
			$em->persist($employer);
			$em->flush();

			if ($request->isXmlHttpRequest()) {
				return new \Symfony\Component\HttpFoundation\JsonResponse([]);
			}

			$session = $this->get('session');
			$session->getFlashBag()->add('success', $translator->trans('flash.success.company.saved'));
			
			return $this->redirect($this->generateUrl('fe_employer_company_index'));
		}

		$vars['company'] = $company;
		$vars['form'] = $form->createView();
		$vars['title'] = $translator->trans('employer.menu.company');

		return $this->render('employer_company_index.html.twig', $vars);
	}

	# Vacancy

	public function vacancyIndexAction(Request $request)
	{
		$session = $this->get('session');
		$translator = $this->get('translator');
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.vacancy.no_company'));
			return $this->render('employer_vacancy_empty.html.twig');
		}

		$vacancies = $company->getVacancies();
		
		if ($vacancies->isEmpty()) {
			return $this->render('employer_vacancy_empty.html.twig');
		}

		$vars['vacancies'] = $vacancies;
		$vars['title'] = $translator->trans('employer_vacancy_index.title');

		return $this->render('employer_vacancy_index.html.twig', $vars);
	}

	public function vacancyNewAction(Request $request)
	{
		$translator = $this->get('translator');
		$session = $this->get('session');
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
		}

		$vacancy = new Vacancy();

		$form = $this->createForm(new VacancyType($this->get('Helper')), $vacancy);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$vacancy->setCompany($company);
			$vacancy->setCreated(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($vacancy);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.vacancy.created'));
			
			return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
		}

		$vars['company'] = $company;
		$vars['form'] = $form->createView();
		$vars['title'] = $translator->trans('employer_vacancy_new.title');
		$vars['h1_title'] = $translator->trans('user.data.add_vacancy2');

		return $this->render('employer_vacancy_new.html.twig', $vars);
	}

	public function vacancyEditAction(Request $request, Vacancy $vacancy)
	{
		$translator = $this->get('translator');
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.vacancy.no_company'));
			return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
		}

		if (!$company->getVacancies()->contains($vacancy)) {

		}

		$form = $this->createForm(new VacancyType($this->get('Helper')), $vacancy);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($vacancy);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', $translator->trans('flash.success.vacancy.updated'));
			
			return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
		}

		$vars['form'] = $form->createView();
		$vars['title'] = 'Редактирование вакансии';
		$vars['h1_title'] = $translator->trans('user.data.edit_vacancy');

		return $this->render('employer_vacancy_new.html.twig', $vars);
	}

	public function vacancyDeleteAction(Request $request, Vacancy $vacancy)
	{
		$translator = $this->get('translator');
		$company = $this->getUser()->getCompany();

		if (!$company) {
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.vacancy.no_company'));
			return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
		}

		// Сделать проверку на то, чтобы вакансия принадлежала пользователю.

		$form = $this->createForm(new VacancyType($this->get('Helper')), $vacancy);
		$form->handleRequest($request);

		$em = $this->getDoctrine()->getManager();
		$em->remove($vacancy);
		$em->flush();

		$session = $this->get('session');
		$session->getFlashBag()->add('success', $translator->trans('flash.success.vacancy.deleted'));

		return $this->redirect($this->generateUrl('fe_employer_vacancy_index'));
	}

	# Favorite

	public function favoriteIndexAction()
	{
		$employer = $this->getUser();

		$vars['favoriteResume'] = $employer->getFavoriteResume();
		
		if ($vars['favoriteResume']->isEmpty()) {
			$translator = $this->get('translator');
			$session = $this->get('session');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.no_fresume'));
			return $this->render('employer_favorite_index.html.twig', $vars);
		}

		//$vars['employer'] = $employer;
		$vars['title'] = 'Избранное';


		return $this->render('employer_favorite_index.html.twig', $vars);
	}

	public function favoriteDeleteAction(Vacancy $vacancy)
	{
		$employer = $this->getUser();
		$vacancies = $employer->getFavoriteVacancy();
		
		if ($vacancies->contains($vacancy)) {
			$employer->removeFavoriteVacancy($vacancy);

			$em = $this->getDoctrine()->getManager();
			$em->persist($employer);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('fe_employer_favorite_index'));
	}

	# Response

	public function responseIndexAction()
	{
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			$session = $this->get('session');
			$translator = $this->get('translator');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.company.response'));
			
			$vars['title'] = '';
			$vars['vacancies'] = [];
			return $this->render('employer_response_index.html.twig', $vars);
		}

		$vacancies = $employer->getCompany()->getVacancies();

		if ($vacancies->isEmpty()) {
			$vars['title'] = '';
			$vars['vacancies'] = [];
			return $this->render('employer_response_index.html.twig', $vars);
		}

		foreach ($vacancies as $vacancy) {
			$vacancy->setResponses(0);
			// if ($vacancy->getResponseEmployee()->isEmpty()) {
			// 	$vacancies->removeElement($vacancy);
			// }
		}

		$em = $this->getDoctrine()->getManager()->flush();

		$vars['vacancies'] = $vacancies;
		$vars['title'] = 'Откликнулись';

		return $this->render('employer_response_index.html.twig', $vars);
	}

	# Managment

	public function managmentIndexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$session = $this->get('session');
		$translator = $this->get('translator');
		$employer = $this->getUser();
		$company = $employer->getCompany();

		if (!$company) {
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.company.no_company'));
			
			return $this->redirect($this->generateUrl('fe_employer_company_index'));
		}

		$repository = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Vacancy');
		$vacancies = $repository->findBy(['company' => $company]);

		// Vacancy: up;

		$vacancyUpForm = $this->createForm(new VacancyUpType($company));
		$vacancyUpForm->handleRequest($request);

		if ($vacancyUpForm->isValid()) {
			$vacancy = $vacancyUpForm->get('vacancy')->getData();

			$balance = $employer->getBalance();

			if ($balance < 100) {
				$session->getFlashBag()->add('error', $translator->trans('flash.error.managment.no_money'));
				return $this->redirect($this->generateUrl('fe_employer_managment_index'));
			}

			$endDate = $vacancy->getUp() ? $vacancy->getUp() : new \Datetime();
			$endDate->modify('+ 7 days');

			$vacancy->setUp(new \DateTime($endDate->format('Y-m-d')));
			$employer->setBalance($balance - 100);
			
			$em->persist($vacancy);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.managment.up'));

			return $this->redirect($this->generateUrl('fe_employer_managment_index'));
		}

		$vars['vacancy_up_form'] = $vacancyUpForm->createView();

		// Vacancy: vip;

		$vacancyVipForm = $this->createForm(new VacancyVipType($company));
		$vacancyVipForm->handleRequest($request);

		if ($vacancyVipForm->isValid()) {
			$vacancy = $vacancyVipForm->get('vacancy')->getData();

			$balance = $employer->getBalance();

			if ($balance < 100) {
				$session->getFlashBag()->add('error', $translator->trans('flash.error.managment.no_money'));
				return $this->redirect($this->generateUrl('fe_employer_managment_index'));
			}

			$endDate = $vacancy->getVip() ? $vacancy->getVip() : new \Datetime();
			$endDate->add(new \DateInterval('P7D'));

			$vacancy->setVip(new \DateTime($endDate->format('Y-m-d')));
			$employer->setBalance($balance - 100);
			
			$em->persist($vacancy);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.managment.vip'));

			return $this->redirect($this->generateUrl('fe_employer_managment_index'));
		}

		$vars['vacancy_vip_form'] = $vacancyVipForm->createView();

		// Vacancy: urgent;

		$vacancyUrgentForm = $this->createForm(new VacancyUrgentType($company));
		$vacancyUrgentForm->handleRequest($request);

		if ($vacancyUrgentForm->isValid()) {
			$vacancy = $vacancyUrgentForm->get('vacancy')->getData();

			$balance = $employer->getBalance();

			if ($balance < 100) {
				$session->getFlashBag()->add('error', $translator->trans('flash.error.managment.no_money'));
				return $this->redirect($this->generateUrl('fe_employer_managment_index'));
			}

			$endDate = $vacancy->getUrgent() ? $vacancy->getUrgent() : new \Datetime();
			$endDate->add(new \DateInterval('P7D'));

			$vacancy->setUrgent(new \DateTime($endDate->format('Y-m-d')));
			$employer->setBalance($balance - 100);
			
			$em->persist($vacancy);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.managment.urgent'));

			return $this->redirect($this->generateUrl('fe_employer_managment_index'));
		}

		$vars['vacancy_urgent_form'] = $vacancyUrgentForm->createView();

		// --------------

		$vars['title'] = 'Управление аккаунтом';
		$vars['vacancies'] = $vacancies;

		return $this->render('employer_managment_index.html.twig', $vars);
	}
}