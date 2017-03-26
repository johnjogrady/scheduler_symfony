<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 20/03/2017
 * Time: 08:19
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RosterAssignedEmployee
 *
 * @ORM\Table(name="rosterassignedemployee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RosterAssignedEmployeeRepository")
 */
class RosterAssignedEmployee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employeeId;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Roster")
     * @ORM\JoinColumn(name="roster_id", referencedColumnName="id")
     */
    private $rosterId;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set employeeId
     *
     * @param \AppBundle\Entity\Employee $employeeId
     *
     * @return RosterAssignedEmployee
     */
    public function setEmployeeId(\AppBundle\Entity\Employee $employeeId = null)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * Get employeeId
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * Set rosterId
     *
     * @param \AppBundle\Entity\Roster $rosterId
     *
     * @return RosterAssignedEmployee
     */
    public function setRosterId(\AppBundle\Entity\Roster $rosterId = null)
    {
        $this->rosterId = $rosterId;

        return $this;
    }

    /**
     * Get rosterId
     *
     * @return \AppBundle\Entity\Roster
     */
    public function getRosterId()
    {
        return $this->rosterId;
    }
}
