<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * County
 *
 * @ORM\Table(name="UnavailabilityReason")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnavailabilityReasonRepository")
 */
class UnavailabilityReason
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
    private $unavailabilityReason;

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
     * Set unavailabilityReason
     *
     * @param string $unavailabilityReason
     *
     * @return UnavailabilityReason
     */
    public function setAbsenceReason($unavailabilityReason)
    {
        $this->unavailabilityReason = $unavailabilityReason;

        return $this;
    }

    /**
     * Get unavailabilityReason
     *
     * @return string
     */
    public function getUnavailabilityReason()
    {
        return $this->unavailabilityReason;
    }

    /**
     * Set unavailabilityReason
     *
     * @param string $unavailabilityReason
     *
     * @return UnavailabilityReason
     */
    public function setUnavailabilityReason($unavailabilityReason)
    {
        $this->unavailabilityReason = $unavailabilityReason;

        return $this;
    }

}
