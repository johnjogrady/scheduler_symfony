<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * County
 *
 * @ORM\Table(name="AbsenceReason")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AbsenceReasonRepository")
 */
class AbsenceReason
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
    private $absenceReason;

    public function __toString()
    {
        return $this->absenceReason;
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
     * Set absenceReason
     *
     * @param string $absenceReason
     *
     * @return AbsenceReason
     */
    public function setAbsenceReason($absenceReason)
    {
        $this->absenceReason = $absenceReason;

        return $this;
    }

    /**
     * Get absenceReason
     *
     * @return string
     */
    public function getAbsenceReason()
    {
        return $this->absenceReason;
    }
}
