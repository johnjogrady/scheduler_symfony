<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 18/03/2017
 * Time: 21:55
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
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
    private $customerName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $addressLine3;

    /**
     * @ORM\ManyToOne(targetEntity="County")
     * @ORM\JoinColumn(name="county_postcode", referencedColumnName="id")
     */
    private $countyPostcode;
    /**
     * @ORM\Column(type="string", length=8, nullable=true)
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
     * @ORM\Column(type="string", length=20)
     */
    private $mainContact;

    /**
     * @ORM\ManyToOne(targetEntity="Office")
     * @ORM\JoinColumn(name="managing_office", referencedColumnName="id")
     */
    private $managingOffice;


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
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Customer
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set addressLine1
     *
     * @param string $addressLine1
     *
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * Set mainContact
     *
     * @param string $mainContact
     *
     * @return Customer
     */
    public function setMainContact($mainContact)
    {
        $this->mainContact = $mainContact;

        return $this;
    }

    /**
     * Get mainContact
     *
     * @return string
     */
    public function getMainContact()
    {
        return $this->mainContact;
    }

    /**
     * Set managingOffice
     *
     * @param integer $managingOffice
     *
     * @return Customer
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

    public function __toString()
    {
        return $this->customerName;
    }
}
