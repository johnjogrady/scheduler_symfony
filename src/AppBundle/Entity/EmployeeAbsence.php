<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 20/03/2017
 * Time: 08:21
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee Absence
 *
 * @ORM\Table(name="employeeabsence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeAbsenceRepository")
 */
class EmployeeAbsence
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startTime;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AbsenceReason")
     * @ORM\JoinColumn(name="absence_reason_id", referencedColumnName="id", nullable=true))
     */
    private $absenceReason;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", nullable=true))
     */
    private $employeeId;


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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return EmployeeAbsence
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
     * @return EmployeeAbsence
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
     * Set absenceReason
     *
     * @param \AppBundle\Entity\AbsenceReason $absenceReason
     *
     * @return EmployeeAbsence
     */
    public function setAbsenceReason(\AppBundle\Entity\AbsenceReason $absenceReason = null)
    {
        $this->absenceReason = $absenceReason;

        return $this;
    }

    /**
     * Get absenceReason
     *
     * @return \AppBundle\Entity\AbsenceReason
     */
    public function getAbsenceReason()
    {
        return $this->absenceReason;
    }

    /**
     * Set employeeId
     *
     * @param \AppBundle\Entity\Employee $employeeId
     *
     * @return EmployeeAbsence
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
     * Set approvedBy
     *
     * @param \AppBundle\Entity\User $approvedBy
     *
     * @return EmployeeAbsence
     */

}
