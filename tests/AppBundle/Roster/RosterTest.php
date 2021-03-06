<?php

namespace AppBundle\Tests\Controller;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class RosterControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));

        $date = new \DateTime('2015-04-01');

        //browse to an exsting service user and add a roster to that record
        $crawler = $client->request('GET', '/serviceuser/2');
        $crawler = $client->click($crawler->selectLink('Add New Roster')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_roster[serviceUserId]' => 2,
            'appbundle_roster[rosterStatus]' => 3,
            'appbundle_roster[numberResourcesNeeded]' => 0,
            'appbundle_roster[customerId]' => 8,
            'appbundle_roster[rosterStartTime][date]' => '2017-04-01',
            'appbundle_roster[rosterStartTime][time]' => '08:00',
            'appbundle_roster[rosterEndTime][date]' => '2017-04-01',
            'appbundle_roster[rosterEndTime][time]' => '09:00'));

        $client->submit($form);
        $crawler = $client->request('GET', '/roster');
        $crawler = $client->followRedirect();
        // find the last anchor tag
        $link = $crawler->selectLink('Edit')
            ->last()
            ->link();

        $client->click($link);
        $client->followRedirects(true);


        $this->assertNotRegExp('/TestUnit/', $client->getResponse()->getContent());


    }

}