<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="offices")
 */
class Office
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @ORM\Column(type="string", length=20)
     */
    private $officeName;

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
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongtitude()
    {
        return $this->longtitude;
    }

    /**
     * @param mixed $longtitude
     */
    public function setLongtitude($longtitude)
    {
        $this->longtitude = $longtitude;
    }

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longtitude;



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
     * Set officeName
     *
     * @param string $officeName
     *
     * @return Office
     */
    public function setOfficeName($officeName)
    {
        $this->officeName = $officeName;

        return $this;
    }

    /**
     * Get officeName
     *
     * @return string
     */
    public function getOfficeName()
    {
        return $this->officeName;
    }

    /**
     * Set addressLine1
     *
     * @param string $addressLine1
     *
     * @return Office
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
     * @return Office
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
     * @return Office
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
     * @return Office
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
     * @return Office
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
     * @return Office
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
     * @return Office
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
     * @return Office
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


    public function __toString()
    {
        return $this->officeName;
    }
}
