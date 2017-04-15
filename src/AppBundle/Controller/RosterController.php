<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Roster;
use AppBundle\Entity\RosterStatus;
use AppBundle\Entity\ServiceUser;
use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Roster controller.
 *
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
        $serviceUser = new ServiceUser();
        $form = $this->createForm('AppBundle\Form\RosterType', $roster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $em->flush($roster);

            return $this->redirectToRoute('roster_show', array('id' => $roster->getId()));
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
     * @Route("/newfromsu/serviceUser={serviceUser}", name="roster_new_su")
     * @Method({"GET", "POST"})
     */
    public function newActionfromServiceUser(Request $request, ServiceUser $serviceUser)
    {
        $roster = new Roster();

//        $form = $this->createForm('AppBundle\Form\RosterType', $roster);
        $form = $this->createForm('AppBundle\Form\RosterType', $roster, array(
            'serviceUser' => $serviceUser
        ));
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $em->flush($roster);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
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
     * @Route("/newfromsu/serviceUser={serviceUser},rosterDate={rosterDate}", name="roster_new_su_date")
     * @Method({"GET", "POST"})
     */
    public function newActionfromServiceUserDate(Request $request, ServiceUser $serviceUser, DateTime $rosterDate)
    {
        $roster = new Roster();

        $form = $this->createForm('AppBundle\Form\RosterType', $roster, array(
            'serviceUser' => $serviceUser, 'rosterDate' => $rosterDate
        ));
        var_dump($form);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $em->flush($roster);

            return $this->redirectToRoute('serviceuser_show', array('id' => $serviceUser->getId()));
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
        $editForm = $this->createForm('AppBundle\Form\RosterType', $roster);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('roster_edit', array('id' => $roster->getId()));
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

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roster);
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
