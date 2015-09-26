<?php

namespace OneDayJob\FrontendBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RequestListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
    	$session = $this->container->get('session');
        
        if (!$session->has('locale')) {
        	$sng     = ['AZ', 'AM', 'BY', 'KZ', 'KG', 'MD', 'RU', 'TJ', 'TM', 'UZ', 'UA'];
	    	$germany = ['DE'];

	        $data = file_get_contents('http://api.sypexgeo.net/json/' . $event->getRequest()->getClientIp());
	        $data = json_decode($data);

	        if (null !== $data->country) {
	        	if (in_array($data->country->iso, $sng)) {
	        		$lang = 'ru';
	        	} elseif (in_array($data->country->iso, $germany)) {
	        		$lang = 'de';
	        	} else {
	        		$lang = 'en';
	        	}

	        	$name = 'name_' . $lang;

	        	$locale = [
	        		'lang'    => $lang,
	        		'city'    => $data->city->{$name},
	        		'country' => $data->country->{$name},
	        	];

	        	$session->set('locale', $locale);
	        }
        }

        if (!$session->has('__locale')) {
        	$session->set('__locale', $event->getRequest()->getLocale());
        }

        if (strpos($event->getRequest()->getRequestUri(), '/api') !== false) {
        	$event->getRequest()->setLocale($session->get('__locale'));
        }
    }
}