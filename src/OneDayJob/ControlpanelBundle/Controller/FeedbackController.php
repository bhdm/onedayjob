<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Feedback;
use OneDayJob\ControlpanelBundle\Form\FeedbackType;

/**
 * Feedback controller.
 *
 * @Route("/controlpanel/feedback")
 */
class FeedbackController extends Controller
{

    /**
     * Lists all Feedback entities.
     *
     * @Route("/", name="cp_feedback", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Feedback:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Feedback')
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
     * Creates a new Feedback entity.
     *
     * @Route("/create", name="cp_feedback_create")
     * @Template("OneDayJobControlpanelBundle:Feedback:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Feedback();
        $form = $this->createForm(new FeedbackType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Feedback успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_feedback'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Feedback entity.
     *
     * @Route("/edit/{id}", name="cp_feedback_edit")
     * @Template("OneDayJobControlpanelBundle:Feedback:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Feedback')
            ->find($id);

        $form = $this->createForm(new FeedbackType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Feedback успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_feedback'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Feedback entity.
     *
     * @Route("/delete/{id}", name="cp_feedback_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Feedback')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_feedback'));
    }
}
