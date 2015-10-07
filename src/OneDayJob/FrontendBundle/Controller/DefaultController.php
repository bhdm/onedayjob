<?php

namespace OneDayJob\FrontendBundle\Controller;

use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\FrontendBundle\Form\Type\ResumeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OneDayJob\FrontendBundle\Form\Type\FeedbackType;
use OneDayJob\ApiBundle\Entity\Feedback;
use OneDayJob\FrontendBundle\Form\Type\CompanyType;
use OneDayJob\ApiBundle\Entity\Company;
use OneDayJob\FrontendBundle\Form\Type\VacancyType;
use OneDayJob\ApiBundle\Entity\Vacancy;
use OneDayJob\ApiBundle\Entity\CountryTranslation;
use OneDayJob\ApiBundle\Entity\CountryTranslationRepository;

class DefaultController extends Controller
{
	public function redirectLocaleAction(Request $request)
	{
		/*set_time_limit(0);

		$json = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/json.json');

		$data = (array) json_decode($json);

		foreach ($data as $item) {
			$item = (array) $item;

			if (!empty($item['areas'][0]->areas)) {
				foreach ($item['areas'] as $row) {
					foreach ($row->areas as $iii) {
						$result[$item['name']][] = $iii->name;
					}

					//$result[$item['name']][] = $row->name;
				}
			} else {
				foreach ($item['areas'] as $iii) {
					$iii = (array) $iii;
		 			$result[$item['name']][] = $iii['name'];
		 		}
			}
		}

		$em = $this->getDoctrine()->getManager();

        foreach ($result as $k => $v) {
        	if ($k == 'Другие страны') {
        		continue;
        	}

	        $country = new \OneDayJob\ApiBundle\Entity\Country();
	    	$country->translate('ru')->setTitle($k);
	    	$country->translate('en')->setTitle($k);
	    	$country->translate('de')->setTitle($k);

	    	$em->persist($country);

	    	$country->mergeNewTranslations();
    		foreach ($v as $_city) {
    			$city = new \OneDayJob\ApiBundle\Entity\City();
    			$city->setCountry($country);
		    	$city->translate('ru')->setTitle($_city);
		    	$city->translate('en')->setTitle($_city);
		    	$city->translate('de')->setTitle($_city);

		    	
		    	$em->persist($city);

		    	$city->mergeNewTranslations();
    		}

    		$em->flush();
    	}

        $em->flush();

		echo "<pre>";
		print_r($result);
		die;*/
		// $file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/country.json');

		// $_countries = json_decode($file);

		// $ch = curl_init();
  //       curl_setopt($ch, CURLOPT_URL, 'http://api.vk.com/method/database.getCountries?need_all=1');
  //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  //       curl_setopt($ch, CURLOPT_USERAGENT,'Chrome 43');
  //       $_countries = json_decode(curl_exec($ch))->response;
  //       curl_close($ch);

  //      // $i = 0;

  //       foreach ($_countries as $k) {
		// 	$ch = curl_init();
	 //        curl_setopt($ch, CURLOPT_URL, 'http://api.vk.com/method/database.getCities?lang=ru&country_id='.$k->cid.'&need_all=1&count=1000');
	 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 //        curl_setopt($ch, CURLOPT_USERAGENT,'Chrome 43');
	 //        $_cities[] = json_decode(curl_exec($ch))->response;
	 //        curl_close($ch);

	 //        // if ($i > 5) {
	 //        // 	break;
	 //        // }

	 //        // $i++;
		// }

		// $res = 0;

		// foreach ($_cities as $item) {
		// 	$res += count($item);
		// }

  //       var_dump($res);
  //       die;

		// foreach ($_countries as $id => $titles) {
		// 	foreach ($titles as $k => $v) {
		// 		$ch = curl_init();
		//         curl_setopt($ch, CURLOPT_URL, 'http://api.vk.com/method/database.getCities?lang='.$k.'&country_id='.$id.'&need_all=0&count=1000');
		//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//         curl_setopt($ch, CURLOPT_USERAGENT,'Chrome 43');
		//         $titles->cities[$k] = json_decode(curl_exec($ch))->response;
		//         curl_close($ch);
		// 	}
		// }

		// file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/countries.json', json_encode($_countries));

		// echo "<pre>";

		// print_r($_countries);
		// die;

	 //    $em = $this->getDoctrine()->getManager();

	 //    $file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/countries.json');

		// $_countries = json_decode($file);

		// foreach ($_countries as $item) {
		// 	foreach ($item->cities as $loc => $obj) {
		// 		foreach ($obj as $row) {
		// 			$_temp[$row->cid][$loc] = $row->title;
		// 		}
		// 	}

		// 	$item->cities = $_temp;

		// 	$_temp = null;
		// }

		// $_countries = (array) $_countries;
  //       foreach ($_countries as $row) {
  //       	if (empty($row->cities) || $row->cities === null) {
  //       		continue;
  //       	}

	 //        $country = new \OneDayJob\ApiBundle\Entity\Country();
	 //    	$country->translate('ru')->setTitle($row->ru);
	 //    	$country->translate('en')->setTitle($row->en);
	 //    	$country->translate('de')->setTitle($row->de);

	 //    	$em->persist($country);

	 //    	$country->mergeNewTranslations();
  //   		foreach ($row->cities as $_city) {
  //   			$city = new \OneDayJob\ApiBundle\Entity\City();
  //   			$city->setCountry($country);
		//     	$city->translate('ru')->setTitle($_city['ru']);
		//     	$city->translate('en')->setTitle($_city['en']);
		//     	$city->translate('de')->setTitle($_city['de']);

		    	
		//     	$em->persist($city);

		//     	$city->mergeNewTranslations();
  //   		}

  //   		$em->flush();
  //   	}

  //       $em->flush();

		// die;
		/*$yaml = new \Symfony\Component\Yaml\Parser();

		$data = $yaml->parse(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/odj_specialization.yml'));

		var_dump($data);
die;

	    $em = $this->getDoctrine()->getManager();

	    
		// Сохраняет отрасли
	    foreach ($data as $item) {
	    	$branch = new \OneDayJob\ApiBundle\Entity\Branch();
	    	
	    	$trans = $branch->translate('ru')->setTitle($item['title']);

	    	$branch->addTranslation($trans);

	    	$em->persist($branch);
	    }


	    // Сохраняет специализации
	    $repo = $em->getRepository('OneDayJobApiBundle:Branch');

	    foreach ($data as $item) {
	    	$branch = $repo->find($item['parent_id']);

	    	if ($branch) {
	    		$Specialization = new \OneDayJob\ApiBundle\Entity\Specialization();
	    		$Specialization->setParent($branch);

	    		$trans = $Specialization->translate('ru')->setTitle($item['title']);

		    	$Specialization->addTranslation($trans);

		    	$em->persist($Specialization);
	    	} else {
	    		echo "- <br />";
	    	}
	    }


	    $em->flush();

	    die;*/

	    $parameters = [];

	    $session = $this->get('session');

        if ($session->has('locale')) {
        	$parameters = ['_locale' => $session->get('locale')['lang']];
        }

        $this->get('session')->set('__locale', $request->get('_locale'));

		return $this->redirectToRoute('fe_index', $parameters);
	}

	public function indexAction(Request $request)
	{

        $this->get('session')->set('__locale', $request->get('_locale'));

        $locale =  $request->get('_locale');
        $http_host = $request->getHttpHost();
        $user_ip = $_SERVER["REMOTE_ADDR"];
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip='$user_ip'"));
        $country = $geo["geoplugin_countryName"];

        if($locale == "pp"){
            $loc = $this->determineCountry($country);
            return $this->redirect("http://".$http_host."/".$loc."");
        }

        $repository = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->findBy(array("title" => $country));


        $country_id = ceil($repository[0]->getId() / 3 );

        if($locale == "ru")
            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() - 1)->getTitle();
        elseif($locale == "de")
            $country = $this->getDoctrine()->getRepository("OneDayJobApiBundle:CountryTranslation")->find($repository[0]->getId() + 1)->getTitle();
        $k = 0;

		return $this->render('OneDayJobFrontendBundle:Default:index.html.twig', array('local_country' => $country , 'local_country_id' => $country_id));
	}

	# User -> resume

	public function resumeAction()
	{
		return $this->render('resume.html.twig');
	}

    protected function determineCountry($country){
        switch($country){
            case "Germany" :
                $locale = "de";
                break;
            case "Austria" :
                $locale = "de";
                break;
            case "Russian Federation" :
                $locale = "ru";
                break;
            case "Ukraine" :
                $locale = "ru";
                break;
            case "Kazakhstan" :
                $locale = "ru";
                break;
            case "Belarus" :
                $locale = "ru";
                break;
            default:
                $locale = "en";
        }
        $t=0;
        return $locale;
    }



}