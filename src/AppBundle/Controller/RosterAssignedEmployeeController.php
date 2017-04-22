<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\RosterAssignedEmployee;
use AppBundle\Entity\Roster;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Rosterassignedemployee controller.
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("rosterassignedemployee")
 */
class RosterAssignedEmployeeController extends Controller
{
    /**
     * Lists all rosterAssignedEmployee entities.
     *
     * @Route("/", name="rosterassignedemployee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rosterAssignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findAll();

        return $this->render('rosterassignedemployee/index.html.twig', array(
            'rosterAssignedEmployees' => $rosterAssignedEmployees,
        ));
    }

    /**
     * Creates a new rosterAssignedEmployee entity.
     *
     * @Route("/new/roster={roster}", name="rosterassignedemployee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Roster $roster)
    {
        $rosterAssignedEmployee = new RosterAssignedEmployee();
        $session = $request->getSession();
        $session->start();

        $em = $this->getDoctrine()->getManager();

        $availableEmployees = $em->getRepository('AppBundle:Employee')->findAll();
//        $this->getDoctrine()->getRepository('MyBundle:MyTable')->findBy([], ['distance' => 'ASC']);
        $serviceUser = $roster->getServiceUserId();


//call the check for Do Not Send function to remove employees for whom a Do Not Send relationship exists to this service user
        foreach ($availableEmployees as $key => $id) {
            if ($this->checkForDoNotSend($id->getId(), $serviceUser)) {
                unset($availableEmployees[$key]);
            }
        }

// call the check for Availability function (which calls other subsidiary validation
// functions) and remove any unavailable employees from the displayed list
        foreach ($availableEmployees as $key => $id) {
            if ($this->checkForUnAvailability($id->getId(), $roster->getId())) {
                unset($availableEmployees[$key]);
            }
        }

        foreach ($availableEmployees as $key => $id) {
            if ($availableEmployees[$key]->getLatitude() > 0 && $roster->getServiceUserId()->getLatitude() > 0) {
                $availableEmployees[$key]->setDistance($this->checkDistance(
                    $availableEmployees[$key]->getLatitude(),
                    $availableEmployees[$key]->getLongtitude(),
                    $roster->getServiceUserId()->getLatitude(),
                    $roster->getServiceUserId()->getLongtitude()
                ));
            }


        }



        $form = $this->createForm('AppBundle\Form\RosterAssignedEmployeeType', $rosterAssignedEmployee, array(
            'roster' => $roster));
        $form->handleRequest($request);

        $rosterid = $roster->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was assigned to the Roster!');
            $em = $this->getDoctrine()->getManager();
            $em->persist($rosterAssignedEmployee);
            $em->flush($rosterAssignedEmployee);

            return $this->redirectToRoute('roster_show', array('id' => $rosterid));
        }

        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the employee was not assigned to the Roster');
        }
        return $this->render('rosterassignedemployee/new.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'roster' => $roster,
            'availableEmployees' => $availableEmployees,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates assigns an employee to rosterAssignedEmployee entity.
     *
     * @Route("/assign/{rosterid},{employeeid}", name="rosterassignedemployee_assign")
     * @Method({"GET", "POST"})
     */
    public function assignAction(Request $request, $rosterid, $employeeid)
    {
        $rosterAssignedEmployee = new RosterAssignedEmployee();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $session->start();


        $rosterAssignedEmployees = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByRosterId($rosterid);
        $employee = $em->getRepository('AppBundle:Employee')->find($employeeid);
        $roster = $em->getRepository('AppBundle:Roster')->find($rosterid);
        $rosterAssignedEmployee->setEmployeeId($employee);
        $rosterAssignedEmployee->setRosterId($roster);
        $em = $this->getDoctrine()->getManager();
        $em->persist($rosterAssignedEmployee);
        $session->getFlashBag('notice');
        $session->getFlashBag()->add('notice', 'Success, the Employee was assigned to the Roster!');

        $em->flush($rosterAssignedEmployee);

        return $this->redirectToRoute('roster_show', array('id' => $rosterid));

    }

    /**
     * Finds and displays a rosterAssignedEmployee entity.
     *
     * @Route("/{id}", name="rosterassignedemployee_show")
     * @Method("GET")
     */
    public function showAction(RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($rosterAssignedEmployee);

        return $this->render('rosterassignedemployee/show.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rosterAssignedEmployee entity.
     *
     * @Route("/{id}/edit", name="rosterassignedemployee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $deleteForm = $this->createDeleteForm($rosterAssignedEmployee);
        $session = $request->getSession();
        $session->start();

        $editForm = $this->createForm('AppBundle\Form\RosterAssignedEmployeeType', $rosterAssignedEmployee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was updated on the Roster!');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('roster_show', array('id' => $rosterAssignedEmployee->getRosterId()));

        }
        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the Employee was not was updated on the Roster');
        }


        return $this->render('rosterassignedemployee/edit.html.twig', array(
            'rosterAssignedEmployee' => $rosterAssignedEmployee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rosterAssignedEmployee entity.
     *
     * @Route("/{id}", name="rosterassignedemployee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RosterAssignedEmployee $rosterAssignedEmployee)
    {
        $form = $this->createDeleteForm($rosterAssignedEmployee);
        $session = $request->getSession();
        $session->start();

        $form->handleRequest($request);
        $rosterid = $rosterAssignedEmployee->getRosterId()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rosterAssignedEmployee);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was removed from the Roster!');

            $em->flush();
        }

        return $this->redirectToRoute('roster_show', array('id' => $rosterid));
    }

    /**
     * Creates a form to delete a rosterAssignedEmployee entity.
     *
     * @param RosterAssignedEmployee $rosterAssignedEmployee The rosterAssignedEmployee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RosterAssignedEmployee $rosterAssignedEmployee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rosterassignedemployee_delete', array('id' => $rosterAssignedEmployee->getId())))
            ->setMethod('DELETE')
            ->setAttribute('Label', 'Remove from Roster')
            ->getForm();
    }

    private function checkForDoNotSend($employee, $serviceUser)
    {
        $em = $this->getDoctrine()->getManager();

        //find all all Do not send relationships for that service user
        $doNotSend = $em->getRepository('AppBundle:DoNotSend')->findByServiceUserId($serviceUser->getId());
        //iterate through them and check below to see if array contains employee being checked
        foreach ($doNotSend as $item) {
            $item_ee = $item->getEmployeeId()->getId();
            // check to see if the employee being checked is this item in the Do Not Send list for that employee.
            if ($item_ee === $employee)
                return true;
        }

        return false;

    }

    private function checkForUnAvailability($employee, $rosterid)
    {
        // this function runs the employee through various validation checks before returning true if
        // there any of the following reasons prevent showing the employee as available for the roster in question to the user

        // this one checks to see if the employee has an unavailability recorded permanently for that time of the week
        $unavailable = $this->checkForUnavailableTime($employee, $rosterid);
        // if an unavailability is found, return true and break from the method
        if ($unavailable === true)
            return true;
        // this one checks to see if the employee has an planned absence recorded which overlaps with the time of the roster

        $onAbsence = $this->checkForAbsence($employee, $rosterid);
        // if an absence is found which overlaps, return true and break from the method
        if ($onAbsence === true)
            return true;

        // this one checks to see if the is already rostered at the time of the unfilled roster

        $onAbsence = $this->checkForAlreadyAssigned($employee, $rosterid);
        // if another roster for the same employee is found which overlaps, return true and break from the method
        if ($onAbsence === true)
            return true;

        return false;

    }

    private function checkForUnavailableTime($employee, $rosterid)
    {
        $em = $this->getDoctrine()->getManager();
        $employeeUnavailability = $em->getRepository('AppBundle:EmployeeUnavailability')->findByEmployeeId($employee);
        $roster = $em->getRepository('AppBundle:Roster')->find($rosterid);
        // retrieves roster object for which check is required

        $requiredStart = $roster->getRosterStartTime();
        // cast to timestamp object (represented as int)
        $dateAsInt = $requiredStart->getTimestamp();
        // retrieves as number from 0 to 6 representing day of week- Sunday is 0, Monday is 1 etc.
        $day = date('w', $dateAsInt);
        // iterate through unavailable times and search for matches
        foreach ($employeeUnavailability as $item) {
            $itemday = strval($item->getDayOfWeek());
            if ($day == $itemday) {
                // to do handles unavailability after roster ok, but not before
                $unavailableStartTime = date("H:i:s", ($item->getStartTime()->getTimestamp()));
                $checkedStartTime = date("H:i:s", strval($roster->getRosterStartTime()->getTimestamp()));
                $unavailableEndTime = date("H:i:s", ($item->getEndTime()->getTimestamp()));
                $checkedEndTime = date("H:i:s", strval($roster->getRosterEndTime()->getTimestamp()));
                if ($unavailableEndTime > $checkedStartTime && $unavailableStartTime < $checkedEndTime)

                    // implementation of this algorithm
                    //http://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
                    return true;
            }
            return false;
        }

        return false;

    }

    private function checkForAbsence($employee, $rosterid)
    {
        $em = $this->getDoctrine()->getManager();
        $absenceforEmployee = $em->getRepository('AppBundle:EmployeeAbsence')->findByEmployeeId($employee);

        $roster = $em->getRepository('AppBundle:Roster')->find($rosterid);
        $rosterStartTime = $roster->getRosterStartTime();
        $rosterEndTime = $roster->getRosterEndTime();
        foreach ($absenceforEmployee as $item) {
            $absencestart = $item->getStartTime();
            $absenceend = $item->getEndTime();
            if ($absenceend > $rosterStartTime && $absencestart < $rosterEndTime)
                return true;
        }
        return false;

    }

    private function checkForAlreadyAssigned($employee, $rosterid)
    {
        $em = $this->getDoctrine()->getManager();
        $assignedRosters = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByEmployeeId($employee);

        $roster = $em->getRepository('AppBundle:Roster')->find($rosterid);
        $rosterStartTime = $roster->getRosterStartTime();
        $rosterEndTime = $roster->getRosterEndTime();
        foreach ($assignedRosters as $item) {
            $assignedstart = $item->getRosterId()->getRosterStartTime();
            $assignedend = $item->getRosterId()->getRosterEndTime();
            if ($assignedend > $rosterStartTime && $assignedstart < $rosterEndTime)
                return true;
        }
        return false;

    }

    private function checkDistance($empllat, $empllong, $sulat, $sulong)
    {

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $empllat . ',' . $empllong . '&destinations=' . $sulat . ',' . $sulong . '&mode=driving&key=AIzaSyAzyO_ohGHVXjjO7rQZLKhQUzHF0iMchds';
        // get response as json
        $json_response = file_get_contents($url);
        $result = json_decode($json_response, true);

        if ($result['status'] == "ZERO_RESULTS") {

            $distance = 0;
        } else {
            $distance = $result['rows'][0]['elements'][0]['distance']['text'];
        }
        return $distance;

    }

    public function sortByDistance($a, $b)
    {
        return strcmp($a->distance, $b->distance);
    }

}
