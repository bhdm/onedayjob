<?php

namespace OneDayJob\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use OneDayJob\FrontendBundle\Form\Type\ResumeType;
use OneDayJob\FrontendBundle\Form\Type\ResumeVipType;
use OneDayJob\FrontendBundle\Form\Type\ResumeUpType;
use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\ApiBundle\Entity\Vacancy;
use OneDayJob\ApiBundle\Entity\Institution;

class EmployeeController extends Controller
{
	# Favorite

	public function favoriteIndexAction()
	{
		$employee = $this->getUser();
		$vacancies = $employee->getFavoriteVacancy();
		
		if ($vacancies->isEmpty()) {
			$translator = $this->get('translator');
			$session = $this->get('session');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.no_favorites'));
			return $this->render('employee_favorite_index.html.twig');
		}

		$vars['vacancies'] = $vacancies;
		$vars['title'] = 'Избранное';

		return $this->render('employee_favorite_index.html.twig', $vars);
	}

	public function companyIndexAction()
	{
		$employee = $this->getUser();
		$companies = $employee->getFavoriteCompany();
		
		if ($companies->isEmpty()) {
			$translator = $this->get('translator');
			$session = $this->get('session');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.no_companies'));
		}

		$vars['title'] = 'Избранное';

		return $this->render('employee_company_index.html.twig', $vars);
	}

	# Resume

	public function resumeIndexAction(Request $request)
	{
		$employee = $this->getUser();
		$resume = $employee->getResume();

		if (!$resume) {
			$vars['title'] = '';
			return $this->render('employee_resume_empty.html.twig', $vars);
		}

		$vars['resume'] = $resume;
		$vars['title'] = 'Резюме';

		return $this->render('employee_resume_index.html.twig', $vars);
	}

	public function resumeNewAction(Request $request)
	{
		$translator = $this->get('translator');
		$employee = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$session = $this->get('session');

		if ($employee->getResume()) {
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.resume.already_exists'));
			return $this->redirect($this->generateUrl('fe_employee_resume_index'));
		}

		$resume = new Resume();
		$resume->addEducation(new \OneDayJob\ApiBundle\Entity\Education());
		$resume->addExperience(new \OneDayJob\ApiBundle\Entity\Experience());

		$form = $this->createForm(new ResumeType($this->get('Helper')), $resume);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$resume->setEmployee($employee);
			$employee->setResume($resume);

			foreach ($resume->getEducation() as $education) {
				$education->setResume($resume);
				$em->persist($education);
			}

			foreach ($resume->getExperience() as $experience) {
				$experience->setResume($resume);
				$em->persist($experience);
			}

			$resume->setCreated(new \DateTime());

			$image = $em->getRepository('OneDayJobApiBundle:Image')->findOneBy(['title' => $employee->getId()]);

			if ($image) {
				$resume->setImage($image);
			}

			$em->persist($resume);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.resume.created'));

			return $this->redirect($this->generateUrl('fe_employee_resume_index'));
		}

		$vars['resume'] = $resume;
		$vars['form'] = $form->createView();
		$vars['title'] = 'Создание Резюме';

		return $this->render('employee_resume_new.html.twig', $vars);
	}

	public function resumeEditAction(Request $request)
	{
		$translator = $this->get('translator');
		// Сделать проверку на то, чтобы резюме принадлежало пользователю.
		$employee = $this->getUser();
		$em = $this->getDoctrine()->getManager();

		$resume = $employee->getResume();

		$originalEducation = new ArrayCollection();

		foreach ($resume->getEducation() as $education) {
			$originalEducation->add($education);
		}

		if ($originalEducation->isEmpty()) {
			$resume->getEducation()->add(new \OneDayJob\ApiBundle\Entity\Education());
		}

		$originalExperience = new ArrayCollection();

		foreach ($resume->getExperience() as $experience) {
			$originalExperience->add($experience);
		}

		if ($originalExperience->isEmpty()) {
			$resume->getExperience()->add(new \OneDayJob\ApiBundle\Entity\Experience());
		}

		$form = $this->createForm(new ResumeType($this->get('Helper')), $resume);
		$form->handleRequest($request);

		if ($form->isValid()) {
			foreach ($originalEducation as $education) {
				if (false === $resume->getEducation()->contains($education)) {
					$em->remove($education);
				}
			}

			foreach ($resume->getEducation() as $education) {
				if ($education->getTitle() === null) {
					$resume->removeEducation($education);
				} else {
					$education->setResume($resume);
					$em->persist($education);
				}
			}

			foreach ($originalExperience as $experience) {
				if (false === $resume->getExperience()->contains($experience)) {
					$em->remove($experience);
				}
			}

			foreach ($resume->getExperience() as $experience) {
				if ($experience->getTitle() === null) {
					$resume->removeExperience($experience);
				} else {
					$experience->setResume($resume);
					$em->persist($experience);
				}				
			}

			$em->persist($resume);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', $translator->trans('flash.success.resume.updated'));

			return $this->redirect($this->generateUrl('fe_employee_resume_edit'));
		}

		$vars['form'] = $form->createView();
		$vars['title'] = 'Редактирование вакансии';

		return $this->render('employee_resume_new.html.twig', $vars);
	}

	public function resumeDeleteAction(Request $request, Resume $resume)
	{
		$translator = $this->get('translator');

		$resume = $this->getUser()->getResume();

		if (!$resume) {
			throw $this->createNotFoundException();
		}

		$form = $this->createForm(new ResumeType($this->get('Helper')), $resume);
		$form->handleRequest($request);

		$em = $this->getDoctrine()->getManager();
		$em->remove($resume);
		$em->flush();

		$session = $this->get('session');
		$session->getFlashBag()->add('success', $translator->trans('flash.warning.resume.deleted'));

		return $this->redirect($this->generateUrl('fe_employee_resume_index') . '#collapse-1');
	}

	# Response

	public function responseIndexAction()
	{
		$employee = $this->getUser();
		$vacancies = $employee->getResponseVacancy();
		
		if ($vacancies->isEmpty()) {
			$translator = $this->get('translator');
			$session = $this->get('session');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.no_responces'));
			return $this->render('employee_favorite_index.html.twig');
		}

		$vars['vacancies'] = $vacancies;
		$vars['title'] = 'Мои отклики';

		return $this->render('employee_favorite_index.html.twig', $vars);
	}

	# Managment

	public function managmentIndexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$session = $this->get('session');
		$translator = $this->get('translator');
		$employee = $this->getUser();
		$resume = $employee->getResume();

		if (!$resume) {
			//$session->getFlashBag()->add('warning', $translator->trans('flash.warning.company.no_company'));
			
			return $this->redirect($this->generateUrl('fe_employee_resume_index'));
		}

		// Resume: up;

		$resumeUpForm = $this->createForm(new ResumeVipType($employee));
		$resumeUpForm->handleRequest($request);
		
		if ($resumeUpForm->isValid()) {
			$resume = $resumeUpForm->get('resume')->getData();

			$balance = $employee->getBalance();

			if ($balance < 100) {
				$session->getFlashBag()->add('error', $translator->trans('flash.error.managment.no_money'));
				
				return $this->redirect($this->generateUrl('fe_employee_managment_index'));
			}

			$endDate = $resume->getUp() ? $resume->getUp() : new \Datetime();
			$endDate->add(new \DateInterval('P7D'));

			$resume->setUp(new \DateTime($endDate->format('Y-m-d')));
			$employee->setBalance($balance - 100);
			
			$em->persist($resume);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.employee_managment.up'));

			return $this->redirect($this->generateUrl('fe_employee_managment_index'));
		}

		$vars['resume_up_form'] = $resumeUpForm->createView();

		// Resume: vip;

		$resumeVipForm = $this->createForm(new ResumeUpType($employee));
		$resumeVipForm->handleRequest($request);

		if ($resumeVipForm->isValid()) {
			$resume = $resumeVipForm->get('resume')->getData();

			$balance = $employee->getBalance();

			if ($balance < 100) {
				$session->getFlashBag()->add('error', $translator->trans('flash.error.managment.no_money'));
				return $this->redirect($this->generateUrl('fe_employee_managment_index'));
			}

			$endDate = $resume->getVip() ? $resume->getVip() : new \Datetime();
			$endDate->add(new \DateInterval('P7D'));

			$resume->setVip($endDate);
			$employee->setBalance($balance - 100);
			
			$em->persist($resume);
			$em->flush();

			$session->getFlashBag()->add('success', $translator->trans('flash.success.employee_managment.vip'));

			return $this->redirect($this->generateUrl('fe_employee_managment_index'));
		}

		$vars['resume_vip_form'] = $resumeVipForm->createView();

		// --------------

		$vars['title'] = 'Управление аккаунтом';

		return $this->render('employee_management.html.twig', $vars);
	}
}