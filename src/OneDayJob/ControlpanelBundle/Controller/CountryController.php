<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Country;
use OneDayJob\ControlpanelBundle\Form\CountryType;

/**
 * Country controller.
 *
 * @Route("/controlpanel/country")
 */
class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     * @Route("/", name="cp_country", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Country:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Country')
            ->createQueryBuilder('a')
            ->getQuery();

        $entities = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->get('page', $page),
            500
        );

        return ['entities' => $entities];
    }
    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="cp_country_create")
     * @Template("OneDayJobControlpanelBundle:Country:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Country();
        $form = $this->createForm(new CountryType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Country успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_country'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/edit/{id}", name="cp_country_edit")
     * @Template("OneDayJobControlpanelBundle:Country:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Country')
            ->find($id);

        $form = $this->createForm(new CountryType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Country успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_country'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/delete/{id}", name="cp_country_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        if ($cities = $entity->getCities()) {
            foreach ($cities as $city) {
                foreach ($city->getTranslations() as $translation) {
                    $city->removeTranslation($translation);
                }

                $em->remove($city);
            }
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_country'));
    }
}
