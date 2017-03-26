<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RosterStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Rosterstatus controller.
 *
 * @Route("rosterstatus")
 */
class RosterStatusController extends Controller
{
    /**
     * Lists all rosterStatus entities.
     *
     * @Route("/", name="rosterstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rosterStatuses = $em->getRepository('AppBundle:RosterStatus')->findAll();

        return $this->render('rosterstatus/index.html.twig', array(
            'rosterStatuses' => $rosterStatuses,
        ));
    }

    /**
     * Finds and displays a rosterStatus entity.
     *
     * @Route("/{id}", name="rosterstatus_show")
     * @Method("GET")
     */
    public function showAction(RosterStatus $rosterStatus)
    {

        return $this->render('rosterstatus/show.html.twig', array(
            'rosterStatus' => $rosterStatus,
        ));
    }
}
