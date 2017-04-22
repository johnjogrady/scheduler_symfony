<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * County
 *
 * @ORM\Table(name="RosterStatus")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RosterStatusRepository")
 */
class RosterStatus
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $rosterStatus;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set countyName
     *
     * @param string $countyName
     *
     * @return County
     */

    /**
     * Add office
     *
     * @param \AppBundle\Entity\Office $office
     *
     * @return County
     */
    public function addOffice(\AppBundle\Entity\Office $office)
    {
        $this->offices[] = $office;

        return $this;
    }

    /**
     * Remove office
     *
     * @param \AppBundle\Entity\Office $office
     */
    public function removeOffice(\AppBundle\Entity\Office $office)
    {
        $this->offices->removeElement($office);
    }

    /**
     * Get offices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffices()
    {
        return $this->offices;
    }


    /**
     * Set rosterStatusName
     *
     * @param string $rosterStatusName
     *
     * @return RosterStatus
     */
    public function setRosterStatusName($rosterStatusName)
    {
        $this->rosterStatusName = $rosterStatusName;

        return $this;
    }

    /**
     * Get rosterStatusName
     *
     * @return string
     */
    public function getRosterStatusName()
    {
        return $this->rosterStatusName;
    }

    /**
     * Set rosterStatus
     *
     * @param string $rosterStatus
     *
     * @return RosterStatus
     */
    public function setRosterStatus($rosterStatus)
    {
        $this->rosterStatus = $rosterStatus;

        return $this;
    }

    /**
     * Get rosterStatus
     *
     * @return string
     */
    public function getRosterStatus()
    {
        return $this->rosterStatus;
    }

    public function __toString()
    {
        return $this->rosterStatus;
    }
}
