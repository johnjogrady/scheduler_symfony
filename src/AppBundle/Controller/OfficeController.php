<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Office;
use AppBundle\Entity\County;
use AppBundle\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $office = new Office();
        $form = $this->createForm('AppBundle\Form\OfficeType', $office);
        $form->handleRequest($request);
        $session = $request->getSession();
        $session->start();

        $geoCoder = new Mapping\geoCodeFunctions();


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // call geocoder class with service user object
            $coordinates = $geoCoder->geocode($office);

            $office->setLatitude($coordinates[0]);
            $office->setLongtitude($coordinates[1]);
            $em->persist($office);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Office was added!');

            $em->flush($office);

            return $this->redirectToRoute('office_show', array('id' => $office->getId()));
        }


        if (['REQUEST_METHOD'] === 'POST') {
            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the Office was not updated');
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
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Office $office)
    {
        $deleteForm = $this->createDeleteForm($office);
        $editForm = $this->createForm('AppBundle\Form\OfficeType', $office);
        $session = $request->getSession();
        $session->start();
        $editForm->handleRequest($request);
        $geoCoder = new Mapping\geoCodeFunctions();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // call geocoder class with service user object
            $coordinates = $geoCoder->geocode($office);

            $office->setLatitude($coordinates[0]);
            $office->setLongtitude($coordinates[1]);
            $em->persist($office);
            $session->getFlashBag('notice');
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Office was updated!');

            $em->flush($office);

            return $this->redirectToRoute('office_show', array('id' => $office->getId()));
        }

        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the Office was not updated');
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
     * @Security("is_granted('ROLE_ADMIN')")
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
     * @Security("is_granted('ROLE_ADMIN')")
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
