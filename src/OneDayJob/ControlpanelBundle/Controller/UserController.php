<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\User;
use OneDayJob\ControlpanelBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/controlpanel/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="cp_user", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:User:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:User')
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
     * Creates a new User entity.
     *
     * @Route("/create", name="cp_user_create")
     * @Template("OneDayJobControlpanelBundle:User:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'User успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_user'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit/{id}", name="cp_user_edit")
     * @Template("OneDayJobControlpanelBundle:User:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:User')
            ->find($id);

        $form = $this->createForm(new UserType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'User успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_user'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/delete/{id}", name="cp_user_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_user'));
    }
}
