<?php
namespace AppBundle\Mapping;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\ServiceUser;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Office;
class geoCodeFunctions

{

// reusable method to take an object of various types- serviceuser, employee, office and geocode the address
// accepts an object that has addressline1/2/3andPostcode private properties
// returns arraywithcoordinates
    public function geocode($geoCodedObject)
    {


        $objectType = get_class($geoCodedObject);
        switch ($objectType) {

            case "AppBundle\Entity\ServiceUser":
                $addressObject = new ServiceUser();

                break;

            case "AppBundle\Entity\Employee":
                $addressObject = new Employee();
                break;

            case "AppBundle\Entity\Office":
                $addressObject = new Office();
                break;
        }

        $addressObject = $geoCodedObject;
        // concatenate address fields from object
        $address = $addressObject->getAddressLine1() . ' ' . $addressObject->getAddressLine2() . ' '
            . $addressObject->getAddressLine3() . ' ' . $addressObject->getCountyPostcode();

        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

        // get response as json
        $json_response = file_get_contents($url);
        $result = json_decode($json_response, true);

        if ($result['status'] == "ZERO_RESULTS") {

            $lat = 0;
            $long = 0;
        } else {
            $lat = $result['results'][0]['geometry']['location']['lat'];
            $long = $result['results'][0]['geometry']['location']['lng'];
        }
        $coordinates = array();
        array_push(
            $coordinates, $lat,
            $long);
        return $coordinates;


    }
}
