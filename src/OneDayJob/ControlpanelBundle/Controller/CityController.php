<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\City;
use OneDayJob\ControlpanelBundle\Form\CityType;

/**
 * City controller.
 *
 * @Route("/controlpanel/city")
 */
class CityController extends Controller
{

    /**
     * Lists all City entities.
     *
     * @Route("/", name="cp_city", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:City:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:City')
            ->createQueryBuilder('a')
            ->getQuery();

        $entities = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->get('page', $page),
            10
        );

        return ['entities' => $entities];
    }
    /**
     * Creates a new City entity.
     *
     * @Route("/create", name="cp_city_create")
     * @Template("OneDayJobControlpanelBundle:City:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new City();
        $form = $this->createForm(new CityType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'City успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_city'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/edit/{id}", name="cp_city_edit")
     * @Template("OneDayJobControlpanelBundle:City:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:City')
            ->find($id);

        $form = $this->createForm(new CityType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'City успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_city'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/delete/{id}", name="cp_city_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:City')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find City entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_city'));
    }
}
