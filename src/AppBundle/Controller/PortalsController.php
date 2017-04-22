<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ServiceUser;
use AppBundle\Entity\Roster;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class PortalsController extends Controller
{

    /**
     * Lists all serviceUser entities.
     * @Security("is_granted('ROLE_SERVICEUSER')")
     * @Route("/portals/serviceuser", name="portal_serviceuser_index")
     * @Method("GET")
     */
    public function serviceuserindexAction()
    {
        $em = $this->getDoctrine()->getManager();


        $authenticationUtils = $this->get('security.authentication_utils')->getLastUsername();
        $user = $em->getRepository('AppBundle:User')->findByEmail($authenticationUtils);
        $serviceUser = $em->getRepository('AppBundle:ServiceUser')->findByRelatedUser($user);
        $thisServiceUser = $serviceUser[0];


        $rosters = $em->getRepository('AppBundle:Roster')->findByServiceUserId($serviceUser);

        return $this->render('portals/serviceuser/index.html.twig', array(
            'rosters' => $rosters,
            'serviceUser' => $thisServiceUser
        ));
    }

    /**
     * Creates a new roster entity.
     *
     * @Security("is_granted('ROLE_SERVICEUSER')")
     * @Route("/newserviceUser={serviceUser}", name="portal_serviceuser_newroster")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, ServiceUser $serviceUser)
    {
        $em = $this->getDoctrine()->getManager();

        $roster = new Roster();
        $session = $request->getSession();
        $session->start();
        $form = $this->createForm('AppBundle\Form\RosterType', $roster, array(
            'serviceUser' => $serviceUser
        ));
        $form->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils')->getLastUsername();

        $user = $em->getRepository('AppBundle:User')->findByEmail($authenticationUtils);
        $currentserviceUser = $em->getRepository('AppBundle:ServiceUser')->findByRelatedUser($user);
        $thisServiceUser = $currentserviceUser[0];

        if ($form->isSubmitted() && $form->isValid()
            // in this portal context need to make sure portal user is not
            // trying to add a roster to a different service user

            && ($thisServiceUser == $roster->getServiceUserId())
        ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roster);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, a roster was added!');

            $em->flush($roster);

            return $this->redirectToRoute('portal_roster_show', array('id' => $roster->getId()));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, You do not have permission to create rosters for other service users');
        }
        return $this->render('portals/serviceuser/newfromsu.html.twig', array(
            'roster' => $roster,
            'serviceUser' => $thisServiceUser,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a serviceUser entity.
     *
     * @Security("is_granted('ROLE_SERVICEUSER')")
     * @Route("/portals/serviceuser/{id}", name="portal_roster_show")
     * @Method("GET")
     */
    public function portalshowAction(Roster $roster)
    {
        $deleteForm = $this->createDeleteForm($roster);
        $em = $this->getDoctrine()->getManager();


        $assignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByRosterId($roster->getId());
        $employees = $em->getRepository('AppBundle:Employee')->findAll();

        return $this->render('portals/serviceuser/show.html.twig', array(
            'roster' => $roster,
            'assignedEmployees' => $assignedEmployees,
            'employees' => $employees,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing roster entity.
     *
     * @Security("is_granted('ROLE_SERVICEUSER')")
     * @Route("/portals/serviceuser/edit/{id}", name="portal_roster_edit")
     * @Method({"GET", "POST"})
     */
    public function portalseditAction(Request $request, Roster $roster)
    {
        $deleteForm = $this->createDeleteForm($roster);
        $editForm = $this->createForm('AppBundle\Form\RosterType', $roster);
        $editForm->handleRequest($request);
        $session = $request->getSession();
        $session->start();
        $authenticationUtils = $this->get('security.authentication_utils')->getLastUsername();
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findByEmail($authenticationUtils);
        $currentserviceUser = $em->getRepository('AppBundle:ServiceUser')->findByRelatedUser($user);
        $thisServiceUser = $currentserviceUser[0];


        if ($editForm->isSubmitted() && $editForm->isValid()
            // in this portal context need to make sure portal user is not
            // trying to reassigne a roster to a different service user

            && ($thisServiceUser == $roster->getServiceUserId())
        ) {
            $this->getDoctrine()->getManager()->flush();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was updated!');

            return $this->redirectToRoute('portal_roster_show', array('id' => $roster->getId()));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Warning, this roster was not updated, you can only save rosters for yourself and not for other service users');
        }

        return $this->render('portals/serviceuser/edit.html.twig', array(
            'roster' => $roster,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    private function createDeleteForm(Roster $roster)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('portal_roster_delete', array('id' => $roster->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a roster entity.
     *
     * @Route("/portals/serviceuser/{id}", name="portal_roster_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Roster $roster)
    {
        $session = $request->getSession();
        $session->start();
        $form = $this->createDeleteForm($roster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roster);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the roster was deleted!');

            $em->flush();
        }

        return $this->redirectToRoute('portal_serviceuser_index');
    }

}
