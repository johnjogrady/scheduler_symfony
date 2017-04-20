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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * Roster controller.
 *
 * @Security("is_granted('ROLE_ADMIN')")
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
        $session = $request->getSession();
        $session->start();

        $form = $this->createForm('AppBundle\Form\ServiceUserType', $serviceUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $geoCoder = new Mapping\geoCodeFunctions();
            $coordinates = $geoCoder->geocode($serviceUser);
            $serviceUser->setLatitude($coordinates[0]);
            $serviceUser->setLongtitude($coordinates[1]);
            $em = $this->getDoctrine()->getManager();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the service user was added!');
            $em->persist($serviceUser);
            $em->flush($serviceUser);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }
//if it's a post and not a get and we got here, Houston we have a problem, better tell the user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the service user was not created');
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

        //create an empty Array and determine dates bounds for THIS month

        $daysThisMonth = [];
        $begin = new \DateTime('first day of this month');
        $begin = new \DateTime($begin->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of this month');
        // iterate through the month and check each day against roster fill array with individual rosters which occur during month

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $rosterforday = $em->getRepository('AppBundle:Roster')->getByDate($i, $serviceUser);
            if (!empty($rosterforday)) {
                $daysThisMonth[$i->format('d')] = $rosterforday;
            }

        }
        //create an empty Array and determine dates bounds for NEXT month
        $daysNextMonth = [];
        $beginNextMonth = new \DateTime('first day of next month');
        $beginNextMonth = new \DateTime($beginNextMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of next month');

        // iterate through the month and check each day against roster fill array with individual rosters which occur during month
        for ($i = $beginNextMonth; $i <= $end; $i->modify('+1 day')) {
            $rosterforday = $em->getRepository('AppBundle:Roster')->getByDate($i, $serviceUser);
            if (!empty($rosterforday)) {
                $daysNextMonth[$i->format('d')] = $rosterforday;
            }
        }

        //create an empty Array and determine dates bounds for LAST month

        $daysLastMonth = [];
        $beginLastMonth = new \DateTime('first day of last month');
        $beginLastMonth = new \DateTime($beginLastMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of last month');

        // iterate through the month and check each day against roster fill array with individual rosters which occur during month

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
//similar to show method but calls geocoder, a little bit of code duplication here which can be refactored later
    public function getCoordinates(Request $request, ServiceUser $serviceUser)
    {
        $deleteForm = $this->createDeleteForm($serviceUser);
        $session = $request->getSession();
        $session->start();

        // create new Geocoder class
        $geoCoder = new Mapping\geoCodeFunctions();

        // call geocoder class with service user object
        $coordinates = $geoCoder->geocode($serviceUser);
        $id = $serviceUser->getId();
        $em = $this->getDoctrine()->getManager();
        $rosters = $em->getRepository('AppBundle:Roster')->findByServiceUserId($id);
        //find employees assigned to rosters for this service user
        $assignedEmployees = $em->getRepository('AppBundle:ServiceUserAssignedEmployee')->findByServiceUserId($id);
        $doNotSends = $em->getRepository('AppBundle:DoNotSend')->findByServiceUserId($id);
        $serviceUser->setLatitude($coordinates[0]);
        $serviceUser->setLongtitude($coordinates[1]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($serviceUser);
        $em->flush($serviceUser);
        $session->getFlashBag('notice');
        $session->getFlashBag()->add('notice', 'Success, the service user was updated!');

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
        $session = $request->getSession();
        $session->start();

        $editForm = $this->createForm('AppBundle\Form\ServiceUserType', $serviceUser);
        $editForm->handleRequest($request);
        $geoCoder = new Mapping\geoCodeFunctions();
        // call geocode function
        $coordinates = $geoCoder->geocode($serviceUser);
        //bind latitude [first array item] and longtitude [second array item] to coordinate values
        $serviceUser->setLatitude($coordinates[0]);
        $serviceUser->setLongtitude($coordinates[1]);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the service user was updated!');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('serviceuser_edit', array('id' => $serviceUser->getId()));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the service user was not updated ');
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
        $session = $request->getSession();
        $session->start();
        $form = $this->createDeleteForm($serviceUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the service user was deleted!');

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
