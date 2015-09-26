<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Resume;
use OneDayJob\ControlpanelBundle\Form\ResumeType;

/**
 * Resume controller.
 *
 * @Route("/controlpanel/resume")
 */
class ResumeController extends Controller
{

    /**
     * Lists all Resume entities.
     *
     * @Route("/", name="cp_resume", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Resume:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Resume')
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
     * Creates a new Resume entity.
     *
     * @Route("/create", name="cp_resume_create")
     * @Template("OneDayJobControlpanelBundle:Resume:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Resume();
        $form = $this->createForm(new ResumeType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Resume успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_resume'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Resume entity.
     *
     * @Route("/edit/{id}", name="cp_resume_edit")
     * @Template("OneDayJobControlpanelBundle:Resume:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Resume')
            ->find($id);

        $form = $this->createForm(new ResumeType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Resume успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_resume'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Resume entity.
     *
     * @Route("/delete/{id}", name="cp_resume_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Resume')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resume entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_resume'));
    }
}
