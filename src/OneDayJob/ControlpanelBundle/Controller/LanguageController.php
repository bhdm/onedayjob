<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Language;
use OneDayJob\ControlpanelBundle\Form\LanguageType;

/**
 * Language controller.
 *
 * @Route("/controlpanel/language")
 */
class LanguageController extends Controller
{

    /**
     * Lists all Language entities.
     *
     * @Route("/", name="cp_language", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Language:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Language')
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
     * Creates a new Language entity.
     *
     * @Route("/create", name="cp_language_create")
     * @Template("OneDayJobControlpanelBundle:Language:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Language();
        $form = $this->createForm(new LanguageType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Language успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_language'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Language entity.
     *
     * @Route("/edit/{id}", name="cp_language_edit")
     * @Template("OneDayJobControlpanelBundle:Language:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Language')
            ->find($id);

        $form = $this->createForm(new LanguageType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Language успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_language'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Language entity.
     *
     * @Route("/delete/{id}", name="cp_language_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_language'));
    }
}
