<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ServiceUser;
use AppBundle\Repository\ServiceUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Serviceuser controller.
 *
 * @Route("serviceuser")
 */
class ServiceUserController extends Controller
{
    /**
     * Lists all serviceUser entities.
     *
     * @Route("/", name="serviceuser_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $serviceUsers = $em->getRepository('AppBundle:ServiceUser')->findAllOrderedByName();
        return $this->render('serviceuser/index.html.twig', array(
            'serviceUsers' => $serviceUsers,
        ));
    }

    /**
     * Creates a new serviceUser entity.
     *
     * @Route("/new", name="serviceuser_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $serviceUser = new Serviceuser();
        $form = $this->createForm('AppBundle\Form\ServiceUserType', $serviceUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceUser);
            $em->flush($serviceUser);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }

        return $this->render('serviceuser/new.html.twig', array(
            'serviceUser' => $serviceUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a serviceUser entity.
     *
     * @Route("/{id}", name="serviceuser_show")
     * @Method("GET")
     */
    public function showAction(ServiceUser $serviceUser)
    {
        $deleteForm = $this->createDeleteForm($serviceUser);
        $em = $this->getDoctrine()->getManager();

        $countyId = $serviceUser->getCountyPostcode();
        $officeId = $serviceUser->getManagingOffice();
        $countyName = $em->getRepository('AppBundle:County')->find($countyId);
        $officeName = $em->getRepository('AppBundle:Office')->find($officeId);

        return $this->render('serviceuser/show.html.twig', array(
            'serviceUser' => $serviceUser,
            'officeName' => $officeName,
            'countyName' => $countyName,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing serviceUser entity.
     *
     * @Route("/{id}/edit", name="serviceuser_edit")
     * @Method({"GET"})
     */
    public function updateAction($id)
    {
        // get reference to our repository
        $em = $this->getDoctrine()->getManager();
        $serviceUser = $em->getRepository('AppBundle:ServiceUser')->find($id);
        $deleteForm = $this->createDeleteForm($serviceUser);
        $editForm = $this->createForm('AppBundle\Form\ServiceUserType', $serviceUser);
        if (!$serviceUser) {
            throw $this->createNotFoundException(
                'No service user found for id ' . $id
            );
        }
        $counties = $em->getRepository('AppBundle:County')->findAll();
        $offices = $em->getRepository('AppBundle:Office')->findAll();

        if (null == $serviceUser) {
            $message = 'sorry, no ServiceUser with id = ' . $id . ' could be retrieved from the database';
            $templateName = 'message';

            return $this->app['twig']->render($templateName . '.html.twig');
        } else {
            // route user to update page for product
            // output the detail of product in HTML table
            return $this->render('serviceuser/edit.html.twig', array(
                'serviceUser' => $serviceUser,
                'counties' => $counties,
                'offices' => $offices,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
    }

    /**
     * Displays a form to edit an existing serviceUser entity.
     *
     * @Route("/{id}/edit", name="serviceuser_processedit")
     * @Method({"POST"})
     */
    public function processUpdateAction()
    {
        // get reference to our repository
        $em = $this->getDoctrine()->getManager();
        $id = filter_input(INPUT_POST, 'Id');
        $editedServiceUser = $em->getRepository('AppBundle:ServiceUser')->find($id);
        $editedServiceUser->setId(filter_input(INPUT_POST, 'Id'));
        $editedServiceUser->setFirstName(filter_input(INPUT_POST, 'firstName'));
        $editedServiceUser->setLastName(filter_input(INPUT_POST, 'lastName'));
        $editedServiceUser->setAddressLine1(filter_input(INPUT_POST, 'addressLine1'));
        $editedServiceUser->setAddressLine2(filter_input(INPUT_POST, 'addressLine2'));
        $editedServiceUser->setAddressLine3(filter_input(INPUT_POST, 'addressLine3'));
        $editedServiceUser->setCountyPostcode(filter_input(INPUT_POST, 'countyPostcode'));
        $editedServiceUser->setEirCode(filter_input(INPUT_POST, 'eirCode'));
        $editedServiceUser->setMobileTelephone(filter_input(INPUT_POST, 'mobileTelephone'));
        $editedServiceUser->setLandlineTelephone(filter_input(INPUT_POST, 'landlineTelephone'));
        $editedServiceUser->setManagingOffice(filter_input(INPUT_POST, 'managingOffice'));
        if (filter_input(INPUT_POST, 'startDate') == "")
            $editedServiceUser->setStartDate(NULL);
        else
            $editedServiceUser->setStartDate(new \DateTime(filter_input(INPUT_POST, 'startDate')));

        if (filter_input(INPUT_POST, 'finishDate') == "")
            $editedServiceUser->setFinishDate(NULL);
        else
            $editedServiceUser->setFinishDate(new \DateTime(filter_input(INPUT_POST, 'finishDate')));

        if (isset($_POST['isActive']))
            $editedServiceUser->setIsActive(1);
        else
            $editedServiceUser->setIsActive(0);
        var_dump($editedServiceUser);
        $em->flush();

        return $this->redirectToRoute('serviceuser_index');
    }


    /**
     * Deletes a serviceUser entity.
     *
     * @Route("/{id}", name="serviceuser_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ServiceUser $serviceUser)
    {
        $form = $this->createDeleteForm($serviceUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($serviceUser);
            $em->flush();
        }

        return $this->redirectToRoute('serviceuser_index');
    }

    /**
     * Creates a form to delete a serviceUser entity.
     *
     * @param ServiceUser $serviceUser The serviceUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ServiceUser $serviceUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('serviceuser_delete', array('id' => $serviceUser->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
