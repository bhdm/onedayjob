<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Branch;
use OneDayJob\ControlpanelBundle\Form\BranchType;

/**
 * Branch controller.
 *
 * @Route("/controlpanel/branch")
 */
class BranchController extends Controller
{

    /**
     * Lists all Branch entities.
     *
     * @Route("/", name="cp_branch", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Branch:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Branch')
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
     * Creates a new Branch entity.
     *
     * @Route("/create", name="cp_branch_create")
     * @Template("OneDayJobControlpanelBundle:Branch:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Branch();
        $form = $this->createForm(new BranchType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Branch успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_branch'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Branch entity.
     *
     * @Route("/edit/{id}", name="cp_branch_edit")
     * @Template("OneDayJobControlpanelBundle:Branch:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Branch')
            ->find($id);

        $form = $this->createForm(new BranchType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Branch успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_branch'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Branch entity.
     *
     * @Route("/delete/{id}", name="cp_branch_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Branch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Branch entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_branch'));
    }
}
