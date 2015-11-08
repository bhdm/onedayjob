<?php

namespace OneDayJob\ApiBundle\Controller;

use OneDayJob\ApiBundle\Entity\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;
use OneDayJob\ApiBundle\Entity\Country;
use OneDayJob\ApiBundle\Entity\Vacancy;
use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\ApiBundle\Entity\Image;
use OneDayJob\ApiBundle\Entity\Specialization;
use OneDayJob\ApiBundle\Entity\Company;

class DefaultController extends Controller
{
	public function profileImageUploadAction(Request $request)
	{
		$to_json = [];
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		if (!$user) {
			return new JsonResponse($to_json);
		}

		// TODO: Исключить возможность загрузки более 1 фотографии.

		foreach ($request->files as $file) {
			if ($avatar = $user->getImage()) {
				$em->remove($avatar);
			}
			$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

			$image = new Image();
			$image->setFileName($file_name);

			$file->move($image->getAbsolutePath(), $image->getFileName());

			$user->setImage($image);

			$em->persist($image);
			$em->persist($user);
			$em->flush();

			$to_json = ['filelink' => $image->getSrc()];
		}

		return new JsonResponse($to_json);
	}

	public function companyImageUploadAction(Request $request)
	{
		$to_json = [];
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();

		if (!$user) {
			return new JsonResponse($to_json);
		}

//		$company = $user->getCompany();
//
//		if (!$company) {
//			return new JsonResponse($to_json);
//		}

		// TODO: Исключить возможность загрузки более 1 фотографии.

		foreach ($request->files as $file) {
//			if ($logo = $company->getImage()) {
//				$em->remove($logo);
//			}

			$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

			$image = new Image();
			$image->setFileName($file_name);

			$file->move($image->getAbsolutePath(), $image->getFileName());

//			$company->setImage($image);

            $em->persist($image);
            $em->flush();
            $em->refresh($image);

			//$to_json = ['filelink' => $this->get('liip_imagine.cache.manager')->getBrowserPath($image->getSrc(), 'logo_outbound')];
            $to_json = ['filelink' => $this->get('liip_imagine.cache.manager')->getBrowserPath($image->getSrc(), 'profile_inset'), 'imageId' => $image->getId()];
			break;
		}

		return new JsonResponse($to_json);
	}

	public function resumeImageUploadAction(Request $request)
	{
		$to_json = [];
		$em = $this->getDoctrine()->getManager();
		$employee = $this->getUser();

		if (!$employee) {
			return new JsonResponse($to_json);
		}

//		$resume = $employee->getResume();
//
//		if (!$resume) {
//			return new JsonResponse([]);
//		}

		// TODO: Исключить возможность загрузки более 1 фотографии.

		foreach ($request->files as $file) {
//			if ($avatar = $resume->getImage()) {
//				$em->remove($avatar);
//			}

			$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

			$image = new Image();
			$image->setFileName($file_name);

			$file->move($image->getAbsolutePath(), $image->getFileName());

//			$resume->setImage($image);

			$em->persist($image);
			$em->flush();
			$em->refresh($image);

			$to_json = ['filelink' => $this->get('liip_imagine.cache.manager')->getBrowserPath($image->getSrc(), 'profile_inset'), 'imageId' => $image->getId()];
		}

		return new JsonResponse($to_json);
	}

    public function companyGalleryImageUploadAction(Request $request){
        $to_json = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $image_id = array();
        $image_src = array();
        $i = 0;

        if (!$user) {
            return new JsonResponse($to_json);
        }

        foreach ($request->files as $file) {

            $file_name = sha1(uniqid()) .'.'. $file->guessExtension();

            $image = new Gallery();
            $image->setFileName($file_name);

            $file->move($image->getAbsolutePath(), $image->getFileName());

            $em->persist($image);
            $em->flush();
            $em->refresh($image);

            $image_id =  $image->getId();
            $image_src = $image->getSrc();

            $to_json[$i] = ['filelink' => $this->get('liip_imagine.cache.manager')->getBrowserPath($image_src, 'profile_inset'), 'imageId' => $image_id];
            $i++;
        }



        return new JsonResponse($to_json);

    }

	public function countryCitiesAction(Request $request)
	{
		$cities = $this
			->getDoctrine()
			->getRepository('OneDayJobApiBundle:City')
			->createQueryBuilder('c')
			->where('c.country = :country')
			->setParameter('country', $request->get('country_id'))
            ->orderBy('c.id', 'ASC')
			->getQuery()
			->getResult();
//		$locale = $request->getLocale();

		foreach ($cities as $city) {
			$result[] = [
				'id' => $city->getId(),
				'title' => $city->translate("ru")->getTitle()
			];
		}

		return new JsonResponse($result);
	}

	public function vacancyAction(Request $request)
	{
		$vacancy = $this
			->getDoctrine()
			->getRepository('OneDayJobApiBundle:Vacancy')
			->find($request->get('id'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($vacancy->setViews($vacancy->getViews() + 1));
		$em->flush();

		$helper = $this->get('Helper');

		$currency = $helper->getCurrency();
		$employment = $helper->getEmployment();
		$work_experience = $helper->getExperience();

		$data['id'] = $vacancy->getId();
		$data['title'] = $vacancy->getTitle();
		$data['city'] = $vacancy->getCity()->translate()->getTitle();
		$data['employment'] = $employment[$vacancy->getEmployment()];
		$data['work_experience'] = $this->get('translator')->trans('form.vacancy.work_experience') . ' ' . $work_experience[$vacancy->getWorkExperience()];
		$data['created'] = $vacancy->getCreated()->format('d.m.Y');
		$data['description'] = $vacancy->getExtra();
		$data['skill'] = $vacancy->getSkill();
		$data['salary'] = number_format($vacancy->getSalaryPerMonth());
		$data['currency'] = $currency[$vacancy->getCurrency()];
		$data['company_name'] = $vacancy->getCompany()->getName();
		$data['company_link'] = $this->generateUrl('fe_company', ['id' => $vacancy->getCompany()->getId()]);

		return new JsonResponse(['vacancy' => $data]);
	}

	public function resumeAction(Resume $resume)
	{
		$helper = $this->get('Helper');

		$currency = $helper->getCurrency();

		foreach ($resume->getLanguages() as $item) {
			$languages[]['title'] = $item->getTitle();
		}

		foreach ($resume->getEducation() as $item) {
			$education[]['title'] = $item->getTitle();
		}

		foreach ($resume->getExperience() as $item) {
			$experience[]['title'] = $item->getTitle();
		}

		$data['id'] = $resume->getId();
		$data['name'] = $resume->getFirstName() .' '. $resume->getLastName();
		$data['location'] = $resume->getCity()->getCountry()->translate()->getTitle() . ', ' . $resume->getCity()->translate()->getTitle();
		$data['created'] = $resume->getCreated()->format('d.m.Y');
		$data['salary'] = number_format($resume->getSalary());
		$data['currency'] = $currency[$resume->getCurrency()];
		$data['phone'] = $resume->getPhone();
		$data['email'] = $resume->getEmail();
		$data['skype'] = $resume->getSkype();
		$data['education'] = isset($education) ? $education : '';
		$data['languages'] = isset($languages) ? $languages : '';
		$data['experience'] = isset($experience) ? $experience : '';
		$data['comment'] = $resume->getExtra();
		$data['specialty'] = $resume->getSpecialty();
		$data['image'] = $resume->getImageSrc();

		return new JsonResponse(['resume' => $data]);
	}

	public function vacancyFavoriteAction(Vacancy $vacancy)
	{
		$user = $this->getUser();
		$vacancies = $user->getFavoriteVacancy();

		if (!$vacancies->contains($vacancy)) {
			$user->addFavoriteVacancy($vacancy);
		}
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return new JsonResponse([]);
	}

	public function countriesAction()
	{
		$countries = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Country')->findAll();

		$to_json = [];

		foreach ($countries as $country) {
			$data['id'] = $country->getId();
			$data['title'] = $country->translate()->getTitle();

			$to_json[] = $data;
		}

		return new JsonResponse($to_json);
	}

	public function getCompaniesAction(Request $request)
	{
		$companies = $this
			->getDoctrine()
				->getRepository('OneDayJobApiBundle:Company')
				->createQueryBuilder('c')
				->select('c.id, c.name')
				->where('c.city = :city')
				->setParameter('city', $request->get('city_id', 0))
				->getQuery()
				->getArrayResult();

		return new JsonResponse($companies ? $companies : false);
	}

	public function specializationsAction(Request $request)
	{
		$specializations = $this
			->getDoctrine()
			->getRepository('OneDayJobApiBundle:Specialization')
			->createQueryBuilder('s')
			->where('s.parent = :parent')
			->setParameter('parent', $request->get('branch_id'))
			->getQuery()
			->getResult();

		$locale = $request->getLocale();

		foreach($specializations as $specialization) {
			$result[] = [
				'id'    => $specialization->getId(),
				'title' => $specialization->translate($locale)->getTitle()
			];
		}

		return new JsonResponse($result);
	}

	public function profileEditAction(Request $request)
	{
		$data = $request->get('fos_user_profile_form', null);

		$result = false;

		if ($data) {
			$user = $this->getUser();

	        $user->setEmail($data['email']);
	        $user->setFirstName($data['first_name']);
	        $user->setLastName($data['last_name']);
	        $user->setBirthdate(new \DateTime(implode('-', $data['birthdate'])));

	        if (!empty($data['current_password'])) {
	        	$user->setPlainPassword($data['current_password']);
	        }

	        $this->get('fos_user.user_manager')->updateUser($user, false);
	        $this->getDoctrine()->getManager()->flush();
	        $result = true;
		}
		
		return new JsonResponse(['success' => $result]);
	}

	public function addVacancyToFavoriteAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$vacancy = $em
			->getRepository('OneDayJobApiBundle:Vacancy')
			->find($request->get('id'));

		$user = $this->getUser();

		if ($user->getFavoriteVacancy()->contains($vacancy)) {
			$user->removeFavoriteVacancy($vacancy);

			$response['status'] = 'deleted';
		} else {
			$user->addFavoriteVacancy($vacancy);

			$response['status'] = 'added';
		}

		$em->persist($user);
		$em->flush();

		$response['count'] = $user->getFavoriteVacancy()->count();
		$response['vacancies'] = $this->renderView('_vacancy_block.html.twig', ['vacancies' => $user->getFavoriteVacancy()]);

		return new JsonResponse($response);
	}

	public function addCompanyToFavoriteAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$company = $em
			->getRepository('OneDayJobApiBundle:Company')
			->find($request->get('id'));

		$user = $this->getUser();

		if ($user->getFavoriteCompany()->contains($company)) {
			$user->removeFavoriteCompany($company);

			$response['status'] = 'deleted';
		} else {
			$user->addFavoriteCompany($company);

			$response['status'] = 'added';
		}

		$em->persist($user);
		$em->flush();
		
		return new JsonResponse($response);
	}

	public function addResumeToFavoriteAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$resume = $em
			->getRepository('OneDayJobApiBundle:Resume')
			->find($request->get('id'));

		$user = $this->getUser();

		if ($user->getFavoriteResume()->contains($resume)) {
			$user->removeFavoriteResume($resume);
			$resume->removeFavoriteUser($user);

			$response['status'] = 'deleted';
		} else {
			$user->addFavoriteResume($resume);
			$resume->addFavoriteUser($user);

			$response['status'] = 'added';
		}

		$em->persist($user);

		$em->flush();

		$response['count'] = $user->getFavoriteResume()->count();
		
		return new JsonResponse($response);
	}

	public function vacancyResponseAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$vacancy = $em->getRepository('OneDayJobApiBundle:Vacancy')->find($request->get('id'));
		$user = $this->getUser();

		if ($user->getResponseVacancy()->contains($vacancy)) {
			$responses = $vacancy->getResponses();
			
			if ($responses > 0) {
				$vacancy->setResponses($responses - 1);
			}

			$user->removeResponseVacancy($vacancy);
			$response['status'] = 'deleted';
		} else {
			$user->addResponseVacancy($vacancy);

			$responses = $vacancy->getResponses();
			$vacancy->setResponses($responses + 1);

			$response['status'] = 'added';
		}

		$em->persist($user);
		$em->flush();

		$response['count'] = $user->getResponseVacancy()->count();
		$response['vacancies'] = $this->renderView('_vacancy_block.html.twig', ['vacancies' => $user->getResponseVacancy()]);
		
		return new JsonResponse($response);
	}

	public function responceUserDeleteAction(Request $request)
	{
		$em      = $this->getDoctrine()->getEntityManager();
		$vacancy = $em->getRepository('OneDayJobApiBundle:Vacancy')->find($request->get('vacancy_id'));
		$user    = $em->getRepository('OneDayJobApiBundle:User')->find($request->get('user_id'));

		if ($user->getResponseVacancy()->contains($vacancy)) {
			$user->removeResponseVacancy($vacancy);
			$response['status'] = 'deleted';
			$em->flush();
		}

		$response['count'] = $user->getResponseVacancy()->count();
		
		return new JsonResponse($response);
	}

	public function vacancyDeleteAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$vacancy = $em->getRepository('OneDayJobApiBundle:Vacancy')->find($request->get('id'));
		
		if ($this->getUser()->getCompany()->getVacancies()->contains($vacancy)) {
			$em->remove($vacancy);
			$em->flush();

			$response['success'] = true;
		} else {
			$response['success'] = false;
		}

		return new JsonResponse($response);
	}
}
