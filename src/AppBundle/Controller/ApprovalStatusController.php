<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApprovalStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Approvalstatus controller.
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("approvalstatus")
 */
class ApprovalStatusController extends Controller
{
    /**
     * Lists all approvalStatus entities.
     *
     * @Route("/", name="approvalstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $approvalStatuses = $em->getRepository('AppBundle:ApprovalStatus')->findAll();

        return $this->render('approvalstatus/index.html.twig', array(
            'approvalStatuses' => $approvalStatuses,
        ));
    }

    /**
     * Creates a new approvalStatus entity.
     *
     * @Route("/new", name="approvalstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $approvalStatus = new Approvalstatus();
        $form = $this->createForm('AppBundle\Form\ApprovalStatusType', $approvalStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($approvalStatus);
            $em->flush($approvalStatus);

            return $this->redirectToRoute('approvalstatus_show', array('id' => $approvalStatus->getId()));
        }

        return $this->render('approvalstatus/new.html.twig', array(
            'approvalStatus' => $approvalStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a approvalStatus entity.
     *
     * @Route("/{id}", name="approvalstatus_show")
     * @Method("GET")
     */
    public function showAction(ApprovalStatus $approvalStatus)
    {
        $deleteForm = $this->createDeleteForm($approvalStatus);

        return $this->render('approvalstatus/show.html.twig', array(
            'approvalStatus' => $approvalStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing approvalStatus entity.
     *
     * @Route("/{id}/edit", name="approvalstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ApprovalStatus $approvalStatus)
    {
        $deleteForm = $this->createDeleteForm($approvalStatus);
        $editForm = $this->createForm('AppBundle\Form\ApprovalStatusType', $approvalStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('approvalstatus_edit', array('id' => $approvalStatus->getId()));
        }

        return $this->render('approvalstatus/edit.html.twig', array(
            'approvalStatus' => $approvalStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a approvalStatus entity.
     *
     * @Route("/{id}", name="approvalstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ApprovalStatus $approvalStatus)
    {
        $form = $this->createDeleteForm($approvalStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($approvalStatus);
            $em->flush();
        }

        return $this->redirectToRoute('approvalstatus_index');
    }

    /**
     * Creates a form to delete a approvalStatus entity.
     *
     * @param ApprovalStatus $approvalStatus The approvalStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApprovalStatus $approvalStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('approvalstatus_delete', array('id' => $approvalStatus->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
