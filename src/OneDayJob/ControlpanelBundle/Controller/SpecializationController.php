<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Specialization;
use OneDayJob\ControlpanelBundle\Form\SpecializationType;

/**
 * Specialization controller.
 *
 * @Route("/controlpanel/specialization")
 */
class SpecializationController extends Controller
{

    /**
     * Lists all Specialization entities.
     *
     * @Route("/", name="cp_specialization", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Specialization:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Specialization')
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
     * Creates a new Specialization entity.
     *
     * @Route("/create", name="cp_specialization_create")
     * @Template("OneDayJobControlpanelBundle:Specialization:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Specialization();
        $form = $this->createForm(new SpecializationType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Specialization успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_specialization'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Specialization entity.
     *
     * @Route("/edit/{id}", name="cp_specialization_edit")
     * @Template("OneDayJobControlpanelBundle:Specialization:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Specialization')
            ->find($id);

        $form = $this->createForm(new SpecializationType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Specialization успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_specialization'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Specialization entity.
     *
     * @Route("/delete/{id}", name="cp_specialization_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Specialization')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Specialization entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_specialization'));
    }
}
