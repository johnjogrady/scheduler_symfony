<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 18/03/2017
 * Time: 22:18
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Roster
 *
 * @ORM\Table(name="roster")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RosterRepository")
 */
class Roster

{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ServiceUser")
     * @ORM\JoinColumn(name="serviceUserId", referencedColumnName="id")
     */
    private $serviceUserId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rosterStartTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rosterEndTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RosterStatus")
     * @ORM\JoinColumn(name="roster_status", referencedColumnName="id")
     */
    private $rosterStatus;

    /**
     * @ORM\Column(type="integer", length=5, options={"default"=1})
     */
    private $numberResourcesNeeded;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     *
     */
    private $customerId;

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
     * @param integer $serviceUserId
     *
     * @return Roster
     */
    public function setServiceUserId($serviceUserId)
    {
        $this->serviceUserId = $serviceUserId;

        return $this;
    }

    /**
     * Get serviceUserId
     *
     * @return integer
     */
    public function getServiceUserId()
    {
        return $this->serviceUserId;
    }

    /**
     * Set rosterStartTime
     *
     * @param \DateTime $rosterStartTime
     *
     * @return Roster
     */
    public function setRosterStartTime($rosterStartTime)
    {
        $this->rosterStartTime = $rosterStartTime;

        return $this;
    }

    /**
     * Get rosterStartTime
     *
     * @return \DateTime
     */
    public function getRosterStartTime()
    {
        return $this->rosterStartTime;
    }

    /**
     * Set rosterEndTime
     *
     * @param \DateTime $rosterEndTime
     *
     * @return Roster
     */
    public function setRosterEndTime($rosterEndTime)
    {
        $this->rosterEndTime = $rosterEndTime;

        return $this;
    }

    /**
     * Get rosterEndTime
     *
     * @return \DateTime
     */
    public function getRosterEndTime()
    {
        return $this->rosterEndTime;
    }

    /**
     * Set rosterStatus
     *
     * @param integer $rosterStatus
     *
     * @return Roster
     */
    public function setRosterStatus($rosterStatus)
    {
        $this->rosterStatus = $rosterStatus;

        return $this;
    }

    /**
     * Get rosterStatus
     *
     * @return integer
     */
    public function getRosterStatus()
    {
        return $this->rosterStatus;
    }

    /**
     * Set numberResourcesNeeded
     *
     * @param integer $numberResourcesNeeded
     *
     * @return Roster
     */
    public function setNumberResourcesNeeded($numberResourcesNeeded)
    {
        $this->numberResourcesNeeded = $numberResourcesNeeded;

        return $this;
    }

    /**
     * Get numberResourcesNeeded
     *
     * @return integer
     */
    public function getNumberResourcesNeeded()
    {
        return $this->numberResourcesNeeded;
    }

    /**
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Roster
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return integer
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function __toString()
    {
        return strval($this->serviceUserId) . ' ' . date_format($this->rosterEndTime, 'Y-m-d H:i') . ' to ' . date_format($this->rosterEndTime, 'Y-m-d H:i');
    }

}
