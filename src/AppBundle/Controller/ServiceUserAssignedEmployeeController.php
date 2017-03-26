<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ServiceUserAssignedEmployee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ServiceUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Serviceuserassignedemployee controller.
 *
 * @Route("serviceuserassignedemployee")
 */
class ServiceUserAssignedEmployeeController extends Controller
{
    /**
     * Lists all serviceUserAssignedEmployee entities.
     *
     * @Route("/", name="serviceuserassignedemployee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $serviceUserAssignedEmployees = $em->getRepository('AppBundle:ServiceUserAssignedEmployee')->findAll();

        return $this->render('serviceuserassignedemployee/index.html.twig', array(
            'serviceUserAssignedEmployees' => $serviceUserAssignedEmployees,
        ));
    }

    /**
     * Creates a new serviceUserAssignedEmployee entity.
     *
     * @Route("/new/serviceUser={serviceUser}", name="serviceuserassignedemployee_new")
     *
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, ServiceUser $serviceUser)
    {
        $serviceUserAssignedEmployee = new ServiceUserAssignedEmployee();
        $form = $this->createForm('AppBundle\Form\ServiceUserAssignedEmployeeType', $serviceUserAssignedEmployee, array(
            'serviceUser' => $serviceUser
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceUserAssignedEmployee);
            $em->flush($serviceUserAssignedEmployee);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }

        return $this->render('serviceuserassignedemployee/new.html.twig', array(
            'serviceUserAssignedEmployee' => $serviceUserAssignedEmployee,
            'serviceUser' => $serviceUser,

            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a serviceUserAssignedEmployee entity.
     *
     * @Route("/{id}", name="serviceuserassignedemployee_show")
     * @Method("GET")
     */
    public function showAction(ServiceUserAssignedEmployee $serviceUserAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($serviceUserAssignedEmployee);

        return $this->render('serviceuserassignedemployee/show.html.twig', array(
            'serviceUserAssignedEmployee' => $serviceUserAssignedEmployee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing serviceUserAssignedEmployee entity.
     *
     * @Route("/{id}/edit", name="serviceuserassignedemployee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ServiceUserAssignedEmployee $serviceUserAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($serviceUserAssignedEmployee);
        $editForm = $this->createForm('AppBundle\Form\ServiceUserAssignedEmployeeType', $serviceUserAssignedEmployee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('serviceuserassignedemployee_edit', array('id' => $serviceUserAssignedEmployee->getId()));
        }

        return $this->render('serviceuserassignedemployee/edit.html.twig', array(
            'serviceUserAssignedEmployee' => $serviceUserAssignedEmployee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a serviceUserAssignedEmployee entity.
     *
     * @Route("/{id}", name="serviceuserassignedemployee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ServiceUserAssignedEmployee $serviceUserAssignedEmployee)
    {
        $form = $this->createDeleteForm($serviceUserAssignedEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($serviceUserAssignedEmployee);
            $em->flush();
        }

        return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUserAssignedEmployee->getServiceUserId()->getId()));

    }

    /**
     * Creates a form to delete a serviceUserAssignedEmployee entity.
     *
     * @param ServiceUserAssignedEmployee $serviceUserAssignedEmployee The serviceUserAssignedEmployee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ServiceUserAssignedEmployee $serviceUserAssignedEmployee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('serviceuserassignedemployee_delete', array('id' => $serviceUserAssignedEmployee->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
