<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RosterAssignedEmployee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Rosterassignedemployee controller.
 *
 * @Route("rosterassignedemployee")
 */
class RosterAssignedEmployeeController extends Controller
{
    /**
     * Lists all rosterAssignedEmployee entities.
     *
     * @Route("/", name="rosterassignedemployee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rosterAssignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findAll();

        return $this->render('rosterassignedemployee/index.html.twig', array(
            'rosterAssignedEmployees' => $rosterAssignedEmployees,
        ));
    }

    /**
     * Creates a new rosterAssignedEmployee entity.
     *
     * @Route("/new", name="rosterassignedemployee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $rosterAssignedEmployee = new Rosterassignedemployee();
        $form = $this->createForm('AppBundle\Form\RosterAssignedEmployeeType', $rosterAssignedEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rosterAssignedEmployee);
            $em->flush($rosterAssignedEmployee);

            return $this->redirectToRoute('rosterassignedemployee_show', array('id' => $rosterAssignedEmployee->getId()));
        }

        return $this->render('rosterassignedemployee/new.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a rosterAssignedEmployee entity.
     *
     * @Route("/{id}", name="rosterassignedemployee_show")
     * @Method("GET")
     */
    public function showAction(RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($rosterAssignedEmployee);

        return $this->render('rosterassignedemployee/show.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rosterAssignedEmployee entity.
     *
     * @Route("/{id}/edit", name="rosterassignedemployee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($rosterAssignedEmployee);
        $editForm = $this->createForm('AppBundle\Form\RosterAssignedEmployeeType', $rosterAssignedEmployee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rosterassignedemployee_edit', array('id' => $rosterAssignedEmployee->getId()));
        }

        return $this->render('rosterassignedemployee/edit.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rosterAssignedEmployee entity.
     *
     * @Route("/{id}", name="rosterassignedemployee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $form = $this->createDeleteForm($rosterAssignedEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rosterAssignedEmployee);
            $em->flush();
        }

        return $this->redirectToRoute('rosterassignedemployee_index');
    }

    /**
     * Creates a form to delete a rosterAssignedEmployee entity.
     *
     * @param RosterAssignedEmployee $rosterAssignedEmployee The rosterAssignedEmployee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RosterAssignedEmployee $rosterAssignedEmployee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rosterassignedemployee_delete', array('id' => $rosterAssignedEmployee->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
