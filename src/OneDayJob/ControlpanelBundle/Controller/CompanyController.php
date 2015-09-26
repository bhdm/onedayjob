<?php

namespace OneDayJob\ControlpanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OneDayJob\ApiBundle\Entity\Company;
use OneDayJob\ControlpanelBundle\Form\CompanyType;

/**
 * Company controller.
 *
 * @Route("/controlpanel/company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="cp_company", defaults={"page" = 1})
     * @Template("OneDayJobControlpanelBundle:Company:index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OneDayJobApiBundle:Company')
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
     * @Route("/create", name="cp_company_create")
     * @Route("/edit/{id}", name="cp_company_edit")
     * @Template("OneDayJobControlpanelBundle:Company:form.html.twig")
     */
    public function persistAction(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $id ? $em->find('OneDayJobApiBundle:Company', $id) : new Company();

        $form = $this->createForm(new CompanyType(), $entity)->handleRequest($request);

        if ($form->isValid()) {
            if ($file = $form['image']->getData()) {
                if ($_image = $entity->getImage()) {
                    $em->remove($_image);
                }
                
                $image = new \OneDayJob\ApiBundle\Entity\Image();
                $image->setFileName(sha1(uniqid()) .'.'. $file->guessExtension());

                $file->move($image->getAbsolutePath(), $image->getFileName());

                $entity->setImage($image);

                $em->persist($image);
            }
            
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', 'Компания успешно ' . ($id ? 'обновлена' : 'добавлена'));

            return $this->redirectToRoute('cp_company');
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView()
        ];
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/delete/{id}", name="cp_company_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OneDayJobApiBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('cp_company'));
    }
}
