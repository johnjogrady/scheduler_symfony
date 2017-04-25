<?php

namespace AppBundle\Tests\Controller;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class DoNotSendTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));


        //browse to an exsting service user and add a DO NOT FLAG association to that record
        $crawler = $client->request('GET', '/serviceuser/2');
        $crawler = $client->click($crawler->selectLink('Mark an Employee As Do Not Send')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Mark as Do Not Send for this Service User')->form(array(
            'appbundle_donotsend[serviceUserId]' => 2,
            'appbundle_donotsend[employeeId]' => 9));

        $client->submit($form);

        $client->followRedirects(true);
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));


        //browse to an exsting service user and add a DO NOT FLAG association to that record
        $crawler = $client->request('GET', '/serviceuser/2');
        $client->followRedirects(true);


        //check that the service user has a  test record noted as Do Not Send on the service user
        $this->assertRegExp('/Test/', $client->getResponse()->getContent());



    }

}