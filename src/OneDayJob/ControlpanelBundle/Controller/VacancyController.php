<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Vacancy;
use OneDayJob\ControlpanelBundle\Form\VacancyType;

/**
 * Vacancy controller.
 *
 * @Route("/controlpanel/vacancy")
 */
class VacancyController extends Controller
{

    /**
     * Lists all Vacancy entities.
     *
     * @Route("/", name="cp_vacancy", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Vacancy:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Vacancy')
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
     * Creates a new Vacancy entity.
     *
     * @Route("/create", name="cp_vacancy_create")
     * @Template("OneDayJobControlpanelBundle:Vacancy:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Vacancy();
        $form = $this->createForm(new VacancyType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Vacancy успешно добавлен.');

            return $this->redirect($this->generateUrl('cp_vacancy'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }


    /**
     * Displays a form to edit an existing Vacancy entity.
     *
     * @Route("/edit/{id}", name="cp_vacancy_edit")
     * @Template("OneDayJobControlpanelBundle:Vacancy:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Vacancy')
            ->find($id);

        $form = $this->createForm(new VacancyType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Vacancy успешно обновлен.');

            return $this->redirect($this->generateUrl('cp_vacancy'));
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * Deletes a Vacancy entity.
     *
     * @Route("/delete/{id}", name="cp_vacancy_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Vacancy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vacancy entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_vacancy'));
    }
}
