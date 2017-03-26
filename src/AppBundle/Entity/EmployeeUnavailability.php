<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EmployeeUnavailability
 *
 * @ORM\Table(name="EmployeeUnavailability")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeUnavailabilityRepository")
 */
class EmployeeUnavailability
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UnavailabilityReason")
     * @ORM\JoinColumn(name="unavailability_reason_id", referencedColumnName="id", nullable=true))
     */
    private $unavailabilityReason;


    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $dayOfWeek;

    /**
     * @var time
     *
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @var time
     *
     * @ORM\Column(type="time")
     */
    private $endTime;

    public function __toString()
    {
        return $this->unavailabilityReason;
    }


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
     * Set dayOfWeek
     *
     * @param integer $dayOfWeek
     *
     * @return EmployeeUnavailability
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * Get dayOfWeek
     *
     * @return integer
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return EmployeeUnavailability
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return EmployeeUnavailability
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set employeeId
     *
     * @param \AppBundle\Entity\Employee $employeeId
     *
     * @return EmployeeUnavailability
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
     * Set unavailabilityReason
     *
     * @param \AppBundle\Entity\UnavailabilityReason $unavailabilityReason
     *
     * @return EmployeeUnavailability
     */
    public function setUnavailabilityReason(\AppBundle\Entity\UnavailabilityReason $unavailabilityReason = null)
    {
        $this->unavailabilityReason = $unavailabilityReason;

        return $this;
    }

    /**
     * Get unavailabilityReason
     *
     * @return \AppBundle\Entity\UnavailabilityReason
     */
    public function getUnavailabilityReason()
    {
        return $this->unavailabilityReason;
    }
}
