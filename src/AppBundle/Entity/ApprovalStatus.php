<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * County
 *
 * @ORM\Table(name="ApprovalStatus")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApprovalStatusRepository")
 */
class ApprovalStatus
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
    private $approvalStatus;


    public function __toString()
    {
        return $this->approvalStatus;
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
     * Set approvalStatus
     *
     * @param string $approvalStatus
     *
     * @return ApprovalStatus
     */
    public function setApprovalStatus($approvalStatus)
    {
        $this->approvalStatus = $approvalStatus;

        return $this;
    }

    /**
     * Get approvalStatus
     *
     * @return string
     */
    public function getApprovalStatus()
    {
        return $this->approvalStatus;
    }
}
