<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 18/03/2017
 * Time: 22:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Employee")
 */
class Employee
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", length=8)
     */
    private $staffNumber;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=20,nullable=true)
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $addressLine3;

    /**
     * @ORM\ManyToOne(targetEntity="County")
     * @ORM\JoinColumn(name="county_postcode", referencedColumnName="id")
     */
    private $countyPostcode;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $eirCode;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $landlineTelephone;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $mobileTelephone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;


    /**
     * @ORM\ManyToOne(targetEntity="Office")
     * @ORM\JoinColumn(name="managing_office", referencedColumnName="id")
     */
    private $managingOffice;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $finishDate;


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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set staffNumber
     *
     * @param string $staffNumber
     *
     * @return Employee
     */
    public function setStaffNumber($staffNumber)
    {
        $this->staffNumber = $staffNumber;

        return $this;
    }

    /**
     * Get staffNumber
     *
     * @return string
     */
    public function getStaffNumber()
    {
        return $this->staffNumber;
    }

    /**
     * Set addressLine1
     *
     * @param string $addressLine1
     *
     * @return Employee
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    /**
     * Get addressLine1
     *
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * Set addressLine2
     *
     * @param string $addressLine2
     *
     * @return Employee
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    /**
     * Get addressLine2
     *
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * Set addressLine3
     *
     * @param string $addressLine3
     *
     * @return Employee
     */
    public function setAddressLine3($addressLine3)
    {
        $this->addressLine3 = $addressLine3;

        return $this;
    }

    /**
     * Get addressLine3
     *
     * @return string
     */
    public function getAddressLine3()
    {
        return $this->addressLine3;
    }

    /**
     * Set countyPostcode
     *
     * @param integer $countyPostcode
     *
     * @return Employee
     */
    public function setCountyPostcode($countyPostcode)
    {
        $this->countyPostcode = $countyPostcode;

        return $this;
    }

    /**
     * Get countyPostcode
     *
     * @return integer
     */
    public function getCountyPostcode()
    {
        return $this->countyPostcode;
    }

    /**
     * Set eirCode
     *
     * @param string $eirCode
     *
     * @return Employee
     */
    public function setEirCode($eirCode)
    {
        $this->eirCode = $eirCode;

        return $this;
    }

    /**
     * Get eirCode
     *
     * @return string
     */
    public function getEirCode()
    {
        return $this->eirCode;
    }

    /**
     * Set landlineTelephone
     *
     * @param string $landlineTelephone
     *
     * @return Employee
     */
    public function setLandlineTelephone($landlineTelephone)
    {
        $this->landlineTelephone = $landlineTelephone;

        return $this;
    }

    /**
     * Get landlineTelephone
     *
     * @return string
     */
    public function getLandlineTelephone()
    {
        return $this->landlineTelephone;
    }

    /**
     * Set mobileTelephone
     *
     * @param string $mobileTelephone
     *
     * @return Employee
     */
    public function setMobileTelephone($mobileTelephone)
    {
        $this->mobileTelephone = $mobileTelephone;

        return $this;
    }

    /**
     * Get mobileTelephone
     *
     * @return string
     */
    public function getMobileTelephone()
    {
        return $this->mobileTelephone;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Employee
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set managingOffice
     *
     * @param integer $managingOffice
     *
     * @return Employee
     */
    public function setManagingOffice($managingOffice)
    {
        $this->managingOffice = $managingOffice;

        return $this;
    }

    /**
     * Get managingOffice
     *
     * @return integer
     */
    public function getManagingOffice()
    {
        return $this->managingOffice;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Employee
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set finishDate
     *
     * @param \DateTime $finishDate
     *
     * @return Employee
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * Get finishDate
     *
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }


    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName . ' ID:' . $this->staffNumber;
    }
}
