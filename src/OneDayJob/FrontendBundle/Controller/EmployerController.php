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
	#           Company

	public function companyAction(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
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

            $imageId = $request->request->get('fe_company_edit')['imageId'];
            if ($imageId) {
                $image = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Image')->findOneById($imageId);
                if ($image){
                    $company->setImage($image);
                }
            }

            $image_gallery_id = $request->request->get('fe_company_edit')['imageGalleryId'];
            $ids = explode("_", $image_gallery_id);
            if($image_gallery_id){
                for($i = 0;$i < count($ids); $i++){
                    $image_gallery = $this->getDoctrine()->getRepository("OneDayJobApiBundle:Gallery")->findOneById($ids[$i]);
                    if($image_gallery){
//                        $company->addGallery($image_gallery);
                        $image_gallery->setCompany($company);
                        $em->persist($image_gallery);
                    }
                }
            }



			$employer->setCompany($company);


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
        $geo =$this->geoOrientation($request);

		$vars['company'] = $company;
		$vars['form'] = $form->createView();
		$vars['title'] = $translator->trans('employer.menu.company');
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		return $this->render('OneDayJobFrontendBundle:Employer:employer_company_index.html.twig', $vars);
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
			return $this->render('OneDayJobFrontendBundle:Employer:employer_vacancy_empty.html.twig');
		}

		$vacancies = $company->getVacancies();
		
		if ($vacancies->isEmpty()) {
			return $this->render('OneDayJobFrontendBundle:Employer:employer_vacancy_empty.html.twig');
		}
        $geo =$this->geoOrientation($request);

		$vars['vacancies'] = $vacancies;
		$vars['title'] = $translator->trans('employer_vacancy_index.title');
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		return $this->render('OneDayJobFrontendBundle:Employer:employer_vacancy_index.html.twig', $vars);
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
        $geo =$this->geoOrientation($request);

		$vars['company'] = $company;
		$vars['form'] = $form->createView();
		$vars['title'] = $translator->trans('employer_vacancy_new.title');
		$vars['h1_title'] = $translator->trans('user.data.add_vacancy2');
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		return $this->render('OneDayJobFrontendBundle:Employer:employer_vacancy_new.html.twig', $vars);
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
        $geo =$this->geoOrientation($request);

		$vars['form'] = $form->createView();
		$vars['title'] = 'Редактирование вакансии';
		$vars['h1_title'] = $translator->trans('user.data.edit_vacancy');
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		return $this->render('OneDayJobFrontendBundle:Employer:employer_vacancy_new.html.twig', $vars);
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
        $geo =$this->geoOrientation($this->getRequest());
		$employer = $this->getUser();

		$vars['favoriteResume'] = $employer->getFavoriteResume();
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];
		
		if ($vars['favoriteResume']->isEmpty()) {
			$translator = $this->get('translator');
			$session = $this->get('session');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.no_fresume'));
			return $this->render('OneDayJobFrontendBundle:Employer:employer_favorite_index.html.twig', $vars);
		}


		//$vars['employer'] = $employer;
		$vars['title'] = 'Избранное';



		return $this->render('OneDayJobFrontendBundle:Employer:employer_favorite_index.html.twig', $vars);
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
        $geo =$this->geoOrientation($this->getRequest());
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		if (!$company) {
			$session = $this->get('session');
			$translator = $this->get('translator');
			$session->getFlashBag()->add('warning', $translator->trans('flash.warning.company.response'));
			
			$vars['title'] = '';
			$vars['vacancies'] = [];
			return $this->render('OneDayJobFrontendBundle:Employer:employer_response_index.html.twig', $vars);
		}

		$vacancies = $employer->getCompany()->getVacancies();

		if ($vacancies->isEmpty()) {
			$vars['title'] = '';
			$vars['vacancies'] = [];
			return $this->render('OneDayJobFrontendBundle:Employer:employer_response_index.html.twig', $vars);
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

		return $this->render('OneDayJobFrontendBundle:Employer:employer_response_index.html.twig', $vars);
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

        $geo =$this->geoOrientation($request);
		// --------------

		$vars['title'] = 'Управление аккаунтом';
		$vars['vacancies'] = $vacancies;
        $vars['local_country'] = $geo["country"];
        $vars['local_country_id'] = $geo["id"];

		return $this->render('OneDayJobFrontendBundle:Employer:employer_managment_index.html.twig', $vars);
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