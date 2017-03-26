<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceUserDoNotSend
 *
 * @ORM\Table(name="serviceuserdonotsendemployee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceUserDoNotSendEmployeeRepository")
 */
class DoNotSend
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ServiceUser")
     * @ORM\JoinColumn(name="service_user_id", referencedColumnName="id")
     */
    private $serviceUserId;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
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
     * Set serviceUserId
     *
     * @param \AppBundle\Entity\ServiceUser $serviceUserId
     *
     * @return DoNotSend
     */
    public function setServiceUserId(\AppBundle\Entity\ServiceUser $serviceUserId = null)
    {
        $this->serviceUserId = $serviceUserId;

        return $this;
    }

    /**
     * Get serviceUserId
     *
     * @return \AppBundle\Entity\ServiceUser
     */
    public function getServiceUserId()
    {
        return $this->serviceUserId;
    }

    /**
     * Set employeeId
     *
     * @param \AppBundle\Entity\Employee $employeeId
     *
     * @return DoNotSend
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
}
