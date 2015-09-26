<?php

namespace OneDayJob\ApiBundle\Controller;

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

		$company = $user->getCompany();

		if (!$company) {
			return new JsonResponse($to_json);
		}

		// TODO: Исключить возможность загрузки более 1 фотографии.

		foreach ($request->files as $file) {
			if ($logo = $company->getImage()) {
				$em->remove($logo);
			}

			$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

			$image = new Image();
			$image->setFileName($file_name);

			$file->move($image->getAbsolutePath(), $image->getFileName());

			$company->setImage($image);

			$em->persist($image);
			$em->persist($company);
			$em->flush();

			$to_json = ['filelink' => $image->getSrc()];
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

		$resume = $employee->getResume();

		if (!$resume) {
			foreach ($request->files as $file) {
				if ($avatar = $employee->getImage()) {
					$em->remove($avatar);
				}

				$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

				$image = new Image();
				$image->setFileName($file_name);
				$image->setTitle($employee->getId());

				$file->move($image->getAbsolutePath(), $image->getFileName());

				$em->persist($image);
				$em->flush();

				return new JsonResponse(['filelink' => $image->getSrc()]);
			}
		}

		// TODO: Исключить возможность загрузки более 1 фотографии.

		foreach ($request->files as $file) {
			if ($avatar = $employee->getImage()) {
				$em->remove($avatar);
			}

			$file_name = sha1(uniqid()) .'.'. $file->guessExtension();

			$image = new Image();
			$image->setFileName($file_name);

			$file->move($image->getAbsolutePath(), $image->getFileName());

			$resume->setImage($image);

			$em->persist($image);
			$em->persist($resume);
			$em->flush();

			$to_json = ['filelink' => $image->getSrc()];
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
			->orderBy('c.title')
			->getQuery()
			->getArrayResult();

		return new JsonResponse($cities);
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
		$data['city'] = $vacancy->getCity()->getTitle();
		$data['employment'] = $employment[$vacancy->getEmployment()];
		$data['work_experience'] = $work_experience[$vacancy->getWorkExperience()];
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

		foreach ($resume->getInstitutions() as $item) {
			$institutions[] = date_format($item->getYearFrom(), 'Y') 
					. ' - ' 
					. $item->getYearTo()->format('Y') . ' - ' 
					. $item->getTitle() . ', - "' 
					. $item->getSpecialization() . '"';
		}

		foreach ($resume->getLanguages() as $item) {
			$languages[]['title'] = $item->getTitle();
		}

		$data['id'] = $resume->getId();
		$data['name'] = $resume->getFirstName() .' '. $resume->getLastName();
		$data['location'] = $resume->getCountry()->getTitle() . ', ' . $resume->getCity()->getTitle();
		$data['created'] = $resume->getCreated()->format('d.m.Y');
		$data['salary'] = number_format($resume->getSalary());
		$data['currency'] = $currency[$resume->getCurrency()];
		$data['phone'] = $resume->getPhone();
		$data['email'] = $resume->getEmail();
		$data['skype'] = $resume->getSkype();
		$data['institutions'] = $institutions;
		$data['skill'] = $resume->getSkill();
		$data['languages'] = $languages;
		$data['comment'] = $resume->getComment();
		$data['image'] = $resume->getImage() ? $resume->getImage()->getSrc() : null ;

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
			$data['title'] = $country->getTitle();

			$to_json[] = $data;
		}

		return new JsonResponse($to_json);
	}

	public function specializationsAction(Request $request)
	{
		$specializations = $this
			->getDoctrine()
			->getRepository('OneDayJobApiBundle:Specialization')
			->createQueryBuilder('s')
			->where('s.parent = :parent')
			->setParameter('parent', $request->get('branch_id'))
			->orderBy('s.title')
			->getQuery()
			->getArrayResult();

		return new JsonResponse($specializations);
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

			$response['status'] = 'deleted';
		} else {
			$user->addFavoriteResume($resume);

			$response['status'] = 'added';
		}

		$em->flush();
		
		return new JsonResponse($response);
	}

	public function vacancyResponseAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$vacancy = $em->getRepository('OneDayJobApiBundle:Vacancy')->find($request->get('id'));
		$user = $this->getUser();

		if ($user->getResponseVacancy()->contains($vacancy)) {
			$user->removeResponseVacancy($vacancy);
			$response['status'] = 'deleted';
		} else {
			$user->addResponseVacancy($vacancy);
			$response['status'] = 'added';
		}

		$em->persist($user);
		$em->flush();
		
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
