<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DoNotSend;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ServiceUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Donotsend controller.
 *
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("donotsend")
 */
class DoNotSendController extends Controller
{
    /**
     * Lists all doNotSend entities.
     *
     * @Route("/", name="donotsend_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $doNotSends = $em->getRepository('AppBundle:DoNotSend')->findAll();

        return $this->render('donotsend/index.html.twig', array(
            'doNotSends' => $doNotSends,
        ));
    }

    /**
     * Creates a new doNotSend entity.
     *
     * @Route("/new/serviceUser={serviceUser}", name="donotsend_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, ServiceUser $serviceUser)
    {
        $doNotSend = new DoNotSend();
        $session = $request->getSession();
        $session->start();

        $form = $this->createForm('AppBundle\Form\DoNotSendType', $doNotSend, array(
            'serviceUser' => $serviceUser
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doNotSend);
            $session->getFlashBag()->add('notice', 'Success, employee ' . $doNotSend->getEmployeeId() .
                ' has been updated as not to be sent to work with ' . $doNotSend->getServiceUserId()->getFirstName() .
                ' ' . $doNotSend->getServiceUserId()->getLastName() . '!');

            $em->flush($doNotSend);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }
        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the employee was NOT marked DO NOT SEND to this service user');

        }
        return $this->render('donotsend/new.html.twig', array(
            'doNotSend' => $doNotSend,
            'serviceUser' => $serviceUser,

            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a doNotSend entity.
     *
     * @Route("/{id}", name="donotsend_show")
     * @Method("GET")
     */
    public function showAction(DoNotSend $doNotSend)
    {
        $deleteForm = $this->createDeleteForm($doNotSend);

        return $this->render('donotsend/show.html.twig', array(
            'doNotSend' => $doNotSend,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing doNotSend entity.
     *
     * @Route("/{id}/edit", name="donotsend_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DoNotSend $doNotSend)
    {
        $deleteForm = $this->createDeleteForm($doNotSend);
        $session = $request->getSession();
        $session->start();

        $editForm = $this->createForm('AppBundle\Form\DoNotSendType', $doNotSend, array());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $session->getFlashBag()->add('notice', 'Success, employee ' . $doNotSend->getEmployeeId() .
                ' has been updated as not to be sent to work with ' . $doNotSend->getServiceUserId()->getFirstName() .
                ' ' . $doNotSend->getServiceUserId()->getLastName() . '!');


            return $this->redirectToRoute('donotsend_edit', array('id' => $doNotSend->getId()));
        }
        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the employee was NOT updated as  DO NOT SEND to this service user');
        }
        return $this->render('donotsend/edit.html.twig', array(
            'doNotSend' => $doNotSend,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a doNotSend entity.
     *
     * @Route("/{id}", name="donotsend_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DoNotSend $doNotSend)
    {
        $form = $this->createDeleteForm($doNotSend);
        $form->handleRequest($request);
        $session = $request->getSession();
        $session->start();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($doNotSend);
            $session->getFlashBag()->add('notice', 'Success, the designation of employee ' . $doNotSend->getEmployeeId() .
                ' as not to be sent to work with ' . $doNotSend->getServiceUserId()->getFirstName() .
                ' ' . $doNotSend->getServiceUserId()->getLastName() . ' has been removed!');

            $em->flush();
        }

        return $this->redirectToRoute('serviceuser_show', array('id' => $doNotSend->getServiceUserId()->getId()));
    }

    /**
     * Creates a form to delete a doNotSend entity.
     *
     * @param DoNotSend $doNotSend The doNotSend entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DoNotSend $doNotSend)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('donotsend_delete', array('id' => $doNotSend->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
