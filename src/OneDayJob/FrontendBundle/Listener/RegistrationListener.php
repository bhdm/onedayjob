<?php

namespace OneDayJob\FrontendBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $em;

    private $container;

    public function __construct(Doctrine $doctrine, $container)
    {
        $this->container = $container;
        $this->em = $doctrine->getEntityManager();
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED  => 'onRegistrationCompleted',
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialise',
            FOSUserEvents::REGISTRATION_CONFIRMED  => 'onRegistrationConfirmed'
        ];
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        
        if ($user->getSocialId()) {
            $user->setEnabled(true);
            $this->em->persist($user);
            $this->em->flush();

            $url = $this->container->get('router')->generate('fos_user_registration_confirmed');

            $event->getResponse()->setTargetUrl($url);
        }
    }

    public function onRegistrationConfirmed($event)
    {
        $this->container->get('session')->getFlashBag()->clear();
    }

    public function onRegistrationInitialise(UserEvent $event)
    {
        if ($this->container->get('session')->has('social_user_data')) {
            $data = $this->container->get('session')->get('social_user_data');
            
            $user = $event->getUser();
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setEmail($data['email']);
            $user->setSocialId($data['social_id']);

            $this->container->get('session')->remove('social_user_data');
        }
    }
}