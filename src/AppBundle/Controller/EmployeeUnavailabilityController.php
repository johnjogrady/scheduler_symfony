<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmployeeUnavailability;
use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * EmployeeUnavailability controller.
 *
 * @Route("employeeunavailability")
 */
class EmployeeUnavailabilityController extends Controller
{
    /**
     * Lists all employeeUnavailability entities.
     *
     * @Route("/", name="employeeunavailability_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $employeeUnavailability = $em->getRepository('AppBundle:EmployeeUnavailability')->findAll();

        return $this->render('employeeunavailability/index.html.twig', array(
            'employeeUnavailability' => $employeeUnavailability,
        ));
    }

    /**
     * Creates a new EmployeeUnavailability entity.
     *
     * @Route("/new/employee={employee}", name="employeeunavailability_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Employee $employee)
    {
        $employeeUnavailability = new EmployeeUnavailability();
        $form = $this->createForm('AppBundle\Form\EmployeeUnavailabilityType', $employeeUnavailability, array(
            'employee' => $employee));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employeeUnavailability);
            $em->flush($employeeUnavailability);

            return $this->redirectToRoute('employee_show', array('id' => $employeeUnavailability->getEmployeeId()->getId()));
        }

        return $this->render('employeeunavailability/new.html.twig', array(
            'employeeUnavailability' => $employeeUnavailability,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a employeeUnavailability entity.
     *
     * @Route("/{id}", name="employeeunavailability_show")
     * @Method("GET")
     */
    public function showAction(EmployeeUnavailability $employeeUnavailability)
    {
        $deleteForm = $this->createDeleteForm($employeeUnavailability);

        return $this->render('employeeunavailability/show.html.twig', array(
            'employeeUnavailability' => $employeeUnavailability,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing employeeUnavailability entity.
     *
     * @Route("/{id}/edit", name="employeeunavailability_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EmployeeUnavailability $employeeUnavailability)
    {
        $deleteForm = $this->createDeleteForm($employeeUnavailability);
        $editForm = $this->createForm('AppBundle\Form\EmployeeUnavailabilityType', $employeeUnavailability);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('employee_show', array('id' => $employeeUnavailability->getEmployeeId()->getId()));
        }

        return $this->render('employeeunavailability/edit.html.twig', array(
            'employeeUnavailability' => $employeeUnavailability,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a employeeUnavailability entity.
     *
     * @Route("/{id}", name="employeeunavailability_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EmployeeUnavailability $employeeUnavailability)
    {
        $form = $this->createDeleteForm($employeeUnavailability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($employeeUnavailability);
            $em->flush();
        }

        return $this->redirectToRoute('employee_show', array('id' => $employeeUnavailability->getEmployeeId()->getId()));

    }

    /**
     * Creates a form to delete a employeeUnavailability entity.
     *
     * @param EmployeeUnavailability $employeeUnavailability The employeeUnavailability entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EmployeeUnavailability $employeeUnavailability)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employeeunavailability_show', array('id' => $employeeUnavailability->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
