<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UnavailabilityReason;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Unavailabilityreason controller.
 *
 * @Route("unavailabilityreason")
 */
class UnavailabilityReasonController extends Controller
{
    /**
     * Lists all unavailabilityReason entities.
     *
     * @Route("/", name="unavailabilityreason_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $unavailabilityReasons = $em->getRepository('AppBundle:UnavailabilityReason')->findAll();

        return $this->render('unavailabilityreason/index.html.twig', array(
            'unavailabilityReasons' => $unavailabilityReasons,
        ));
    }

    /**
     * Creates a new unavailabilityReason entity.
     *
     * @Route("/new", name="unavailabilityreason_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $unavailabilityReason = new Unavailabilityreason();
        $form = $this->createForm('AppBundle\Form\UnavailabilityReasonType', $unavailabilityReason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unavailabilityReason);
            $em->flush($unavailabilityReason);

            return $this->redirectToRoute('unavailabilityreason_show', array('id' => $unavailabilityReason->getId()));
        }

        return $this->render('unavailabilityreason/new.html.twig', array(
            'unavailabilityReason' => $unavailabilityReason,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a unavailabilityReason entity.
     *
     * @Route("/{id}", name="unavailabilityreason_show")
     * @Method("GET")
     */
    public function showAction(UnavailabilityReason $unavailabilityReason)
    {
        $deleteForm = $this->createDeleteForm($unavailabilityReason);

        return $this->render('unavailabilityreason/show.html.twig', array(
            'unavailabilityReason' => $unavailabilityReason,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing unavailabilityReason entity.
     *
     * @Route("/{id}/edit", name="unavailabilityreason_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UnavailabilityReason $unavailabilityReason)
    {
        $deleteForm = $this->createDeleteForm($unavailabilityReason);
        $editForm = $this->createForm('AppBundle\Form\UnavailabilityReasonType', $unavailabilityReason);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unavailabilityreason_edit', array('id' => $unavailabilityReason->getId()));
        }

        return $this->render('unavailabilityreason/edit.html.twig', array(
            'unavailabilityReason' => $unavailabilityReason,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a unavailabilityReason entity.
     *
     * @Route("/{id}", name="unavailabilityreason_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UnavailabilityReason $unavailabilityReason)
    {
        $form = $this->createDeleteForm($unavailabilityReason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($unavailabilityReason);
            $em->flush();
        }

        return $this->redirectToRoute('unavailabilityreason_index');
    }

    /**
     * Creates a form to delete a unavailabilityReason entity.
     *
     * @param UnavailabilityReason $unavailabilityReason The unavailabilityReason entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UnavailabilityReason $unavailabilityReason)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('unavailabilityreason_delete', array('id' => $unavailabilityReason->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
