<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Elective
 *
 * @ORM\Table(name="elective")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElectiveRepository")
 */
class Elective
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
     * @ORM\Column(name="moduleCode", type="string", length=255, nullable=true)
     */
    private $moduleCode;

    /**
     * @var string
     *
     * @ORM\Column(name="moduleTitle", type="string", length=255)
     */
    private $moduleTitle;

    /**
     * @var int
     *
     * @ORM\Column(name="credit", type="integer", nullable=true)
     */
    private $credit;


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
     * Set moduleCode
     *
     * @param string $moduleCode
     *
     * @return Elective
     */
    public function setModuleCode($moduleCode)
    {
        $this->moduleCode = $moduleCode;

        return $this;
    }

    /**
     * Get moduleCode
     *
     * @return string
     */
    public function getModuleCode()
    {
        return $this->moduleCode;
    }

    /**
     * Set moduleTitle
     *
     * @param string $moduleTitle
     *
     * @return Elective
     */
    public function setModuleTitle($moduleTitle)
    {
        $this->moduleTitle = $moduleTitle;

        return $this;
    }

    /**
     * Get moduleTitle
     *
     * @return string
     */
    public function getModuleTitle()
    {
        return $this->moduleTitle;
    }

    /**
     * Set credit
     *
     * @param integer $credit
     *
     * @return Elective
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return int
     */
    public function getCredit()
    {
        return $this->credit;
    }
}
