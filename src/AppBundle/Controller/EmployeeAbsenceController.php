<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmployeeAbsence;
use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Employeeabsence controller.
 *
 * @Route("employeeabsence")
 */
class EmployeeAbsenceController extends Controller
{
    /**
     * Lists all employeeAbsence entities.
     *
     * @Route("/", name="employeeabsence_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $employeeAbsences = $em->getRepository('AppBundle:EmployeeAbsence')->findAll();

        return $this->render('employeeabsence/index.html.twig', array(
            'employeeAbsences' => $employeeAbsences,
        ));
    }


    /**
     * Creates a new employeeAbsence entity.
     *
     * @Route("/new/employee={employee}", name="employeeabsence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Employee $employee)
    {
        $employeeAbsence = new Employeeabsence();
        $form = $this->createForm('AppBundle\Form\EmployeeAbsenceType', $employeeAbsence, array(
            'employee' => $employee
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employeeAbsence);
            $em->flush($employeeAbsence);

            return $this->redirectToRoute('employee_show', array('id' => $employeeAbsence->getEmployeeId()->getId()));
        }

        return $this->render('employeeabsence/new.html.twig', array(
            'employeeAbsence' => $employeeAbsence,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a employeeAbsence entity.
     *
     * @Route("/{id}", name="employeeabsence_show")
     * @Method("GET")
     */
    public function showAction(EmployeeAbsence $employeeAbsence)
    {
        $deleteForm = $this->createDeleteForm($employeeAbsence);

        return $this->render('employeeabsence/show.html.twig', array(
            'employeeAbsence' => $employeeAbsence,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing employeeAbsence entity.
     *
     * @Route("/{id}/edit", name="employeeabsence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EmployeeAbsence $employeeAbsence)
    {
        $deleteForm = $this->createDeleteForm($employeeAbsence);
        $editForm = $this->createForm('AppBundle\Form\EmployeeAbsenceType', $employeeAbsence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('employee_show', array('id' => $employeeAbsence->getEmployeeId()->getId()));
        }

        return $this->render('employeeabsence/edit.html.twig', array(
            'employeeAbsence' => $employeeAbsence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a employeeAbsence entity.
     *
     * @Route("/{id}", name="employeeabsence_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EmployeeAbsence $employeeAbsence)
    {
        $form = $this->createDeleteForm($employeeAbsence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($employeeAbsence);
            $em->flush();
        }

        return $this->redirectToRoute('employee_show', array('id' => $employeeAbsence->getEmployeeId()->getId()));
    }

    /**
     * Creates a form to delete a employeeAbsence entity.
     *
     * @param EmployeeAbsence $employeeAbsence The employeeAbsence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EmployeeAbsence $employeeAbsence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employeeabsence_show', array('id' => $employeeAbsence->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
