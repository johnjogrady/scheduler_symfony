<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
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
 * Employee controller.
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("employee")
 */
class EmployeeController extends Controller
{
    /**
     * Lists all employee entities.
     *
     * @Route("/", name="employee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


        $employees = $em->getRepository('AppBundle:Employee')->findAll();

        return $this->render('employee/index.html.twig', array(
            'employees' => $employees,
        ));
    }

    /**
     * Creates a new employee entity.
     *
     * @Route("/new", name="employee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $employee = new Employee();
        $session = $request->getSession();
        $session->start();
        $geoCoder = new Mapping\geoCodeFunctions();


        $form = $this->createForm('AppBundle\Form\EmployeeType', $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // call geocoder class with service user object
            $coordinates = $geoCoder->geocode($employee);
            $employee->setLatitude($coordinates[0]);
            $employee->setLongtitude($coordinates[1]);
            $em->persist($employee);
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was added!');

            $em->flush($employee);

            return $this->redirectToRoute('employee_show', array('id' => $employee->getId()));
        }

        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the Employee  was not added');
        }

        return $this->render('employee/new.html.twig', array(
            'employee' => $employee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a employee entity.
     *
     * @Route("/{id}", name="employee_show")
     * @Method("GET")
     */
    public function showAction(Employee $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);
        $em = $this->getDoctrine()->getManager();
        $id = $employee->getId();

        $employeeAbsences = $em->getRepository('AppBundle:EmployeeAbsence')->findByEmployeeId($employee->getId());

        $rostersAssignments = $em->getRepository('AppBundle:RosterAssignedEmployee')->findByEmployeeId($employee->getId());

        //create an empty Array and determine dates bounds for THIS month
        $daysThisMonth = [];
        $begin = new \DateTime('first day of this month');
        $begin = new \DateTime($begin->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of this month');
        // iterate through the month and check each day against roster fill array with individual rosters which occur during month

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            // create an array of all rosters for all employees on that day
            $allRostersForDay = $em->getRepository('AppBundle:Roster')->getByDate($i);
            foreach ($allRostersForDay as $item) {
                //for each day check to see if that employee has any rosters on that day
                $thisEmployeesRosters = $em->getRepository('AppBundle:RosterAssignedEmployee')->getByDateByEmployee($i, $id);
                foreach ($thisEmployeesRosters as $inneritem) {
                    if (!empty($thisEmployeesRosters)) {
                        // if any are found append to the daysthisMonth array with the day of the month as the key
                        $daysThisMonth[$i->format('d')] = $thisEmployeesRosters;
                    }


                }
            }

        }


        //create an empty Array and determine dates bounds for NEXT month
        $daysNextMonth = [];
        $beginNextMonth = new \DateTime('first day of next month');
        $beginNextMonth = new \DateTime($beginNextMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of next month');
        // iterate through the month and check each day against roster fill array with individual rosters which occur during month

        for ($i = $beginNextMonth; $i <= $end; $i->modify('+1 day')) {
            // create an array of all rosters for all employees on that day
            $allRostersForDay = $em->getRepository('AppBundle:Roster')->getByDate($i);
            foreach ($allRostersForDay as $item) {
                //for each day check to see if that employee has any rosters on that day
                $thisEmployeesRosters = $em->getRepository('AppBundle:RosterAssignedEmployee')->getByDateByEmployee($i, $id);
                foreach ($thisEmployeesRosters as $inneritem) {
                    if (!empty($thisEmployeesRosters)) {
                        // if any are found append to the daysthisMonth array with the day of the month as the key
                        $daysNextMonth[$i->format('d')] = $thisEmployeesRosters;
                    }


                }
            }

        }

        //create an empty Array and determine dates bounds for NEXT month
        $daysLastMonth = [];
        $beginLastMonth = new \DateTime('first day of last month');
        $beginLastMonth = new \DateTime($beginLastMonth->format("Y-m-d") . " 00:00:00");

        $end = new \DateTime('last day of last month');
        // iterate through the month and check each day against roster fill array with individual rosters which occur during month

        for ($i = $beginLastMonth; $i <= $end; $i->modify('+1 day')) {
            // create an array of all rosters for all employees on that day
            $allRostersForDay = $em->getRepository('AppBundle:Roster')->getByDate($i);
            foreach ($allRostersForDay as $item) {
                //for each day check to see if that employee has any rosters on that day
                $thisEmployeesRosters = $em->getRepository('AppBundle:RosterAssignedEmployee')->getByDateByEmployee($i, $id);
                foreach ($thisEmployeesRosters as $inneritem) {
                    if (!empty($thisEmployeesRosters)) {
                        // if any are found append to the daysthisMonth array with the day of the month as the key
                        $daysLastMonth[$i->format('d')] = $thisEmployeesRosters;
                    }


                }
            }

        }
        $employeeUnavailability = $em->getRepository('AppBundle:EmployeeUnavailability')->findByEmployeeId($employee->getId());
        return $this->render('employee/show.html.twig', array(
            'employee' => $employee,
            'employeeUnavailability' => $employeeUnavailability,
            'employeeAbsences' => $employeeAbsences,
            'rosters' => $rostersAssignments,
            'daysThisMonth' => $daysThisMonth,
            'beginNextMonth' => $beginNextMonth,
            'daysNextMonth' => $daysNextMonth,
            'beginLastMonth' => $beginLastMonth,
            'daysLastMonth' => $daysLastMonth,

            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing employee entity.
     *
     * @Route("/{id}/edit", name="employee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Employee $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);
        $session = $request->getSession();
        $session->start();
        // create new GeoCoder class
        $geoCoder = new Mapping\geoCodeFunctions();

        // call geocoder class with service user object
        $coordinates = $geoCoder->geocode($employee);

        $editForm = $this->createForm('AppBundle\Form\EmployeeType', $employee);
        $editForm->handleRequest($request);
        $employee->setLatitude($coordinates[0]);
        $employee->setLongtitude($coordinates[1]);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was updated!');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('employee_show', array('id' => $employee->getId()));
        }
        if (['REQUEST_METHOD'] === 'POST') {

            $session->getFlashBag('error');
            $session->getFlashBag()->add('error', 'Error, the Employee was not updated');
        }


        return $this->render('employee/edit.html.twig', array(
            'employee' => $employee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a employee entity.
     *
     * @Route("/{id}", name="employee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Employee $employee)
    {
        $form = $this->createDeleteForm($employee);
        $session = $request->getSession();
        $session->start();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, the Employee was deleted!');

            $em->remove($employee);
            $em->flush();
        }

        return $this->redirectToRoute('employee_index');
    }

    /**
     * Creates a form to delete a employee entity.
     *
     * @param Employee $employee The employee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Employee $employee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employee_delete', array('id' => $employee->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
