<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AbsenceReason;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Absencereason controller.
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("absencereason")
 */
class AbsenceReasonController extends Controller
{
    /**
     * Lists all absenceReason entities.
     *
     * @Route("/", name="absencereason_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $absenceReasons = $em->getRepository('AppBundle:AbsenceReason')->findAll();

        return $this->render('absencereason/index.html.twig', array(
            'absenceReasons' => $absenceReasons,
        ));
    }

    /**
     * Creates a new absenceReason entity.
     *
     * @Route("/new", name="absencereason_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $absenceReason = new Absencereason();
        $form = $this->createForm('AppBundle\Form\AbsenceReasonType', $absenceReason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($absenceReason);
            $em->flush($absenceReason);

            return $this->redirectToRoute('absencereason_show', array('id' => $absenceReason->getId()));
        }

        return $this->render('absencereason/new.html.twig', array(
            'absenceReason' => $absenceReason,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a absenceReason entity.
     *
     * @Route("/{id}", name="absencereason_show")
     * @Method("GET")
     */
    public function showAction(AbsenceReason $absenceReason)
    {
        $deleteForm = $this->createDeleteForm($absenceReason);

        return $this->render('absencereason/show.html.twig', array(
            'absenceReason' => $absenceReason,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing absenceReason entity.
     *
     * @Route("/{id}/edit", name="absencereason_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbsenceReason $absenceReason)
    {
        $deleteForm = $this->createDeleteForm($absenceReason);
        $editForm = $this->createForm('AppBundle\Form\AbsenceReasonType', $absenceReason);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('absencereason_edit', array('id' => $absenceReason->getId()));
        }

        return $this->render('absencereason/edit.html.twig', array(
            'absenceReason' => $absenceReason,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a absenceReason entity.
     *
     * @Route("/{id}", name="absencereason_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AbsenceReason $absenceReason)
    {
        $form = $this->createDeleteForm($absenceReason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($absenceReason);
            $em->flush();
        }

        return $this->redirectToRoute('absencereason_index');
    }

    /**
     * Creates a form to delete a absenceReason entity.
     *
     * @param AbsenceReason $absenceReason The absenceReason entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AbsenceReason $absenceReason)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('absencereason_delete', array('id' => $absenceReason->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
