<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Roster;
use AppBundle\Entity\ServiceUser;
use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * Roster controller.
 *
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("roster")
 */
class RosterController extends Controller
{
    /**
     * Lists all roster entities.
     *
     * @Route("/", name="roster_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rosters = $em->getRepository('AppBundle:Roster')->findAll();

        return $this->render('roster/index.html.twig', array(
            'rosters' => $rosters,
        ));
    }

    /**
     * Creates a new roster entity.
     *
     * @Route("/new", name="roster_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $roster = new Roster();
        $session = $request->getSession();
        $session->start();
        $serviceUser = new ServiceUser();
        $form = $this->createForm('AppBundle\Form\RosterType', $roster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was added!');
            $em->flush($roster);


            return $this->redirectToRoute('roster_show', array('id' => $roster->getId()));
        }

        if (['REQUEST_METHOD'] === 'POST') {
            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the roster was not created');
        }
        return $this->render('roster/new.html.twig', array(
            'roster' => $roster,
            'serviceUser' => $serviceUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new roster entity.
     *
     * @Route("/new/serviceUser={serviceUser}", name="roster_new_su")
     * @Method({"GET", "POST"})
     */
    public function newActionFromServiceUser(Request $request, ServiceUser $serviceUser)
    {
        $roster = new Roster();
        $session = $request->getSession();
        $session->start();


//        $form = $this->createForm('AppBundle\Form\RosterType', $roster);
        $form = $this->createForm('AppBundle\Form\RosterType', $roster, array(
            'serviceUser' => $serviceUser
        ));
        $form->handleRequest($request);

        $session = $request->getSession();
        $session->start();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was created!');

            $em->flush($roster);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }

        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the roster was not created');
        }
        return $this->render('roster/newfromsu.html.twig', array(
            'roster' => $roster,
            'serviceUser' => $serviceUser
        , 'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new roster entity.
     *
     * @Route("/new/serviceUser={serviceUser},rosterDate={rosterDate}", name="roster_new_su_date")
     * @Method({"GET", "POST"})
     */
    public function newActionFromServiceUserDate(Request $request, ServiceUser $serviceUser, DateTime $rosterDate)
    {
        $roster = new Roster();
        $session = $request->getSession();
        $session->start();
        $form = $this->createForm('AppBundle\Form\RosterType', $roster, array(
            'serviceUser' => $serviceUser, 'rosterDate' => $rosterDate
        ));
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was created!');

            $em->flush($roster);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the roster was not created');
        }
        return $this->render('serviceuser/index.html.twig', array(
            'roster' => $roster,
            'serviceUser' => $serviceUser
        , 'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a roster entity.
     *
     * @Route("/{id}", name="roster_show")
     * @Method("GET")
     */
    public function showAction(Roster $roster)
    {
        $deleteForm = $this->createDeleteForm($roster);
        $em = $this->getDoctrine()->getManager();


        $assignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByRosterId($roster->getId());
        $employees = $em->getRepository('AppBundle:Employee')->findAll();






        return $this->render('roster/show.html.twig', array(
            'roster' => $roster,
            'assignedEmployees' => $assignedEmployees,
            'employees' => $employees,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing roster entity.
     *
     * @Route("/{id}/edit", name="roster_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Roster $roster)
    {
        $deleteForm = $this->createDeleteForm($roster);
        $session = $request->getSession();
        $session->start();
        $editForm = $this->createForm('AppBundle\Form\RosterType', $roster);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was updated!');


            return $this->redirectToRoute('roster_show', array('id' => $roster->getId()));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the roster was not edited');
        }
        return $this->render('roster/edit.html.twig', array(
            'roster' => $roster,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a roster entity.
     *
     * @Route("/{id}", name="roster_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Roster $roster)
    {
        $form = $this->createDeleteForm($roster);
        $form->handleRequest($request);
        $session = $request->getSession();
        $session->start();
        $em = $this->getDoctrine()->getManager();

        $rosterAssignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByRosterId($roster->getId());



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roster);
            //due to foreign key dependencies, we must delete roster assignments for this roster before deleting the roster
            foreach ($rosterAssignedEmployees as $item) {
                $em->remove($item);
            }

            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was deleted!');

            $em->flush();
        }


        return $this->redirectToRoute('roster_index');
    }

    /**
     * Creates a form to delete a roster entity.
     *
     * @param Roster $roster The roster entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Roster $roster)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('roster_delete', array('id' => $roster->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
