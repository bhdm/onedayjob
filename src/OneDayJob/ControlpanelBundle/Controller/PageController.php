<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Page;
use OneDayJob\ControlpanelBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/controlpanel/page")
 */
class PageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/", name="cp_page", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Page:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Page')
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
     * Creates a new Page entity.
     *
     * @Route("/create", name="cp_page_create")
     * @Template("OneDayJobControlpanelBundle:Page:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        $form = $this->createForm(new PageType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Page успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_page'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/edit/{id}", name="cp_page_edit")
     * @Template("OneDayJobControlpanelBundle:Page:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Page')
            ->find($id);

        $form = $this->createForm(new PageType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Page успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_page'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/delete/{id}", name="cp_page_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_page'));
    }
}
