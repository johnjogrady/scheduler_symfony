<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * County
 *
 * @ORM\Table(name="county")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountyRepository")
 */
class County
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
     * @ORM\Column(name="countyName", type="string", length=255)
     */
    private $countyName;


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
    public function setCountyName($countyName)
    {
        $this->countyName = $countyName;

        return $this;
    }

    /**
     * Get countyName
     *
     * @return string
     */
    public function getCountyName()
    {
        return $this->countyName;
    }

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

    public function __toString()
    {
        return $this->countyName;
    }
}
