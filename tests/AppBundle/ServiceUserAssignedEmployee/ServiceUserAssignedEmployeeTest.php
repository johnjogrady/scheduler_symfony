<?php

namespace AppBundle\Tests\Controller;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class ServiceUserAssignedEmployeeTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));


        //browse to an exsting service user and add a Assigned Employee FLAG association to that record
        $crawler = $client->request('GET', '/serviceuser/2');
        $crawler = $client->click($crawler->selectLink('Assign An Employee')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Assign Employee to Service User')->form(array(
            'appbundle_serviceuserassignedemployee[serviceUserId]' => 2,
            'appbundle_serviceuserassignedemployee[employeeId]' => 9));

        $client->submit($form);

        //browse to an exsting service user $crawler = $client->request('GET', '/serviceuser/2');
        $client->followRedirects(true);


        $this->assertNotRegExp('/Test/', $client->getResponse()->getContent());


    }

}