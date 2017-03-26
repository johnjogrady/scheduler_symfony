<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Office;
use AppBundle\Entity\County;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Office controller.
 *
 * @Route("office")
 */
class OfficeController extends Controller
{
    /**
     * Lists all office entities.
     *
     * @Route("/", name="office_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $offices = $em->getRepository('AppBundle:Office')->findAll();


        return $this->render('office/index.html.twig', array(
            'offices' => $offices
        ));
    }

    /**
     * Creates a new office entity.
     *
     * @Route("/new", name="office_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $office = new Office();
        $form = $this->createForm('AppBundle\Form\OfficeType', $office);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($office);
            $em->flush($office);

            return $this->redirectToRoute('office_show', array('id' => $office->getId()));
        }

        return $this->render('office/new.html.twig', array(
            'office' => $office,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a office entity.
     *
     * @Route("/{id}", name="office_show")
     * @Method("GET")
     */


    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $office = $em->getRepository('AppBundle:Office')->find($id);
        $deleteForm = $this->createDeleteForm($office);
        $countyId = $office->getCountyPostcode();
        $countyName = $em->getRepository('AppBundle:County')->find($countyId);

        if (!$office) {
            throw $this->createNotFoundException(
                'No officefound for id ' . $id
            );
        }
        $argsArray = [
            'office' => $office,
            'countyName' => $countyName,
            'delete_form' => $deleteForm->createView(),
        ];

        $templateName = 'office/show';
        return $this->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * Displays a form to edit an existing office entity.
     *
     * @Route("/{id}/edit", name="office_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Office $office)
    {
        $deleteForm = $this->createDeleteForm($office);
        $editForm = $this->createForm('AppBundle\Form\OfficeType', $office);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('office_edit', array('id' => $office->getId()));
        }

        return $this->render('office/edit.html.twig', array(
            'office' => $office,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a office entity.
     *
     * @Route("/{id}", name="office_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Office $office)
    {
        $form = $this->createDeleteForm($office);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($office);
            $em->flush();
        }

        return $this->redirectToRoute('office_index');
    }

    /**
     * Creates a form to delete a office entity.
     *
     * @param Office $office The office entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Office $office)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('office_delete', array('id' => $office->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
