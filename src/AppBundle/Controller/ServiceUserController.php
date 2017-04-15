<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Mapping;
use AppBundle\Entity\ServiceUser;
use AppBundle\Entity\Roster;
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

        $serviceUsers = $em->getRepository('AppBundle:ServiceUser')->findAll();


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
            $geoCoder = new Mapping\geoCodeFunctions();
            $coordinates = $geoCoder->geocode($serviceUser);
            $serviceUser->setLatitude($coordinates[0]);
            $serviceUser->setLongtitude($coordinates[1]);
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
        $id = $serviceUser->getId();
        $assignedEmployees = $em->getRepository('AppBundle:ServiceUserAssignedEmployee')->findByServiceUserId($id);
        $doNotSends = $em->getRepository('AppBundle:DoNotSend')->findByServiceUserId($id);
        $geoCoder = new Mapping\geoCodeFunctions();
        $rosters = $em->getRepository('AppBundle:Roster')->findByServiceUserId($id);

        $daysThisMonth = [];
        $begin = new \DateTime('first day of this month');
        $begin = new \DateTime($begin->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of this month');

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $rosterforday = $em->getRepository('AppBundle:Roster')->getByDate($i, $serviceUser);
            if (!empty($rosterforday)) {
                $daysThisMonth[$i->format('d')] = $rosterforday;
            }

        }

        $daysNextMonth = [];
        $beginNextMonth = new \DateTime('first day of next month');
        $beginNextMonth = new \DateTime($beginNextMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of next month');


        for ($i = $beginNextMonth; $i <= $end; $i->modify('+1 day')) {
            $rosterforday = $em->getRepository('AppBundle:Roster')->getByDate($i, $serviceUser);
            if (!empty($rosterforday)) {
                $daysNextMonth[$i->format('d')] = $rosterforday;
            }
        }
        $daysLastMonth = [];
        $beginLastMonth = new \DateTime('first day of last month');
        $beginLastMonth = new \DateTime($beginLastMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of last month');


        for ($i = $beginLastMonth; $i <= $end; $i->modify('+1 day')) {
            $rosterforday = $em->getRepository('AppBundle:Roster')->getByDate($i, $serviceUser);
            if (!empty($rosterforday)) {
                $daysLastMonth[$i->format('d')] = $rosterforday;
            }

        }




        return $this->render('serviceuser/show.html.twig', array(
            'serviceUser' => $serviceUser,
            'rosters' => $rosters,
            'doNotSends' => $doNotSends,
            'daysThisMonth' => $daysThisMonth,
            'beginNextMonth' => $beginNextMonth,
            'daysNextMonth' => $daysNextMonth,
            'beginLastMonth' => $beginLastMonth,
            'daysLastMonth' => $daysLastMonth,
            'assignedEmployees' => $assignedEmployees,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Uses Mapping class to obtain coordinates from Google Maps.
     *
     * @Route("/{id}/getcoordinates", name="serviceuser_getcoordinates")
     * @Method("GET")
     */

    public function getCoordinates(Request $request, ServiceUser $serviceUser)
    {
        $deleteForm = $this->createDeleteForm($serviceUser);
        $geoCoder = new Mapping\geoCodeFunctions();
        $coordinates = $geoCoder->geocode($serviceUser);
        $id = $serviceUser->getId();
        $em = $this->getDoctrine()->getManager();
        $rosters = $em->getRepository('AppBundle:Roster')->findByServiceUserId($id);
        $assignedEmployees = $em->getRepository('AppBundle:ServiceUserAssignedEmployee')->findByServiceUserId($id);
        $doNotSends = $em->getRepository('AppBundle:DoNotSend')->findByServiceUserId($id);
        $serviceUser->setLatitude($coordinates[0]);
        $serviceUser->setLongtitude($coordinates[1]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($serviceUser);
        $em->flush($serviceUser);
        return $this->render('serviceuser/show.html.twig', array(
            'serviceUser' => $serviceUser,
            'rosters' => $rosters,
            'doNotSends' => $doNotSends,
            'assignedEmployees' => $assignedEmployees,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing serviceUser entity.
     *
     * @Route("/{id}/edit", name="serviceuser_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ServiceUser $serviceUser)
    {
        $deleteForm = $this->createDeleteForm($serviceUser);
        $editForm = $this->createForm('AppBundle\Form\ServiceUserType', $serviceUser);
        $editForm->handleRequest($request);
        $geoCoder = new Mapping\geoCodeFunctions();
        $coordinates = $geoCoder->geocode($serviceUser);
        $serviceUser->setLatitude($coordinates[0]);
        $serviceUser->setLongtitude($coordinates[1]);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('serviceuser_edit', array('id' => $serviceUser->getId()));
        }

        return $this->render('serviceuser/edit.html.twig', array(
            'serviceUser' => $serviceUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
