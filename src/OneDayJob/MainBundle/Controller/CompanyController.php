<?php

namespace OneDayJob\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\CompanyType;
use OneDayJob\ApiBundle\Entity\Company;

class CompanyController extends Controller
{
    # Company

    public function get_companiesAction()
    {
        $companies = $this->getDoctrine()//+
        ->getRepository('OneDayJobApiBundle:Company')
            ->createQueryBuilder('c')
            ->select('c, s, i')
            ->leftJoin('c.city', 's')
            ->leftJoin('c.image', 'i')
            ->getQuery()
            ->getResult();



        $_temp1 = [];
        $_temp2 = [];

        $session = $this->get('session');

        foreach ($companies as $company) {

            $number_vacancies = $this->getDoctrine()
                ->getRepository('OneDayJobApiBundle:Vacancy')
                ->createQueryBuilder('n')
                ->select('COUNT(n)')
                ->Where('n.company = :company')
                ->setParameter('company', $company)
                ->getQuery()
                ->getResult();

            $company->setNumberVacancies($number_vacancies[0][1]);


            if ($session->has('locale')) {
                if ($company->getCity()->translate()->getTitle() == $session->get('locale')['city']) {
                    $_temp1[] = $company;
                } else {
                    $_temp2[] = $company;
                }
            } else {
                $_temp1[] = $company;
            }
        }

//        $p = array_merge($_temp1, $_temp2);
//        $t=0;
        return $this->render('OneDayJobFrontendBundle:Company:_company.html.twig', ['companies' => array_merge($_temp1, $_temp2)]);
    }

//    public function indexAction(Request $request)
//    {
//        $geo =$this->geoOrientation($request);
//        $vars['local_country'] = $geo["country"];
//        $vars['local_country_id'] = $geo["id"];
//        $city    = filter_var($request->query->get('city', 0), FILTER_SANITIZE_NUMBER_INT);
//        $company = filter_var($request->query->get('company_id', 0), FILTER_SANITIZE_NUMBER_INT);
//
//        $repository = $this->getDoctrine()->getRepository('OneDayJobApiBundle:Company');
//        $builder = $repository->createQueryBuilder('c');
//
//
//
//        if ($company) {
//            $builder->andWhere("c.name LIKE '%:id%'");
//            $builder->setParameter('id', $company);
//        }
//        if ($city) {
//            $builder->andWhere('c.city = :city');
//            $builder->setParameter('city', $city);
//        }
//
//
//        $vars['cities'] = $this->getDoctrine()->getRepository('OneDayJobApiBundle:City')->findBy([]);
//        $vars['companies'] = $builder->getQuery()->getResult();
//        $vars['title'] = 'Компании';
//
//        return $this->render('OneDayJobFrontendBundle:Company:company_index.html.twig', $vars);
//    }

//    public function companyAction(Company $company)
//    {
//        $geo =$this->geoOrientation($this->getRequest());
//        $vars['local_country'] = $geo["country"];
//        $vars['local_country_id'] = $geo["id"];
//        $vacancies = [];
//
//        foreach ($company->getVacancies() as $vacancy) {
//            $vacancies[$vacancy->getBranch()->translate()->getTitle()][] = $vacancy;
//        }
//
//        ksort($vacancies);
//
//        $vars['company_index'] = $company;
//        $vars['company_vacancies'] = $vacancies;
//        //$vars['title'] = $company->getName();
//        return $this->render('OneDayJobFrontendBundle:Company:company.html.twig', $vars);
//    }

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
}