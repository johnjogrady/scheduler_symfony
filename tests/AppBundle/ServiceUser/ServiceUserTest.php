<?php

namespace PHPUnit\Framework;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceUserTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/serviceuser/');
        $crawler = $client->click($crawler->selectLink('Add new Service User')->link());


        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_serviceuser[firstName]' => 'Test',
            'appbundle_serviceuser[lastName]' => 'OTesty',
            'appbundle_serviceuser[addressLine1]' => 'Cork Street',
            'appbundle_serviceuser[addressLine2]' => 'The Coombe',
            'appbundle_serviceuser[addressLine3]' => 'Dolphins Barn',
            'appbundle_serviceuser[countyPostcode]' => 1001035,
            'appbundle_serviceuser[eirCode]' => 'D081239',
            'appbundle_serviceuser[landlineTelephone]' => '0123456',
            'appbundle_serviceuser[mobileTelephone]' => '9987654',
            'appbundle_serviceuser[isActive]' => true,
            'appbundle_serviceuser[startDate]' => '2017-04-01',
            'appbundle_serviceuser[finishDate]' => '2017-04-02',
            'appbundle_serviceuser[managingOffice]' => 1
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity

        $crawler = $client->click($crawler->selectLink('Edit Service User Record')->link());
        $buttonCrawlerNode = $crawler->selectButton('Submit Changes');
        //create a new form object, nb because we can have more than one form, each one is linked to its submit button
        //fill in new form values
        $form = $buttonCrawlerNode->form(array(
            'appbundle_serviceuser[firstName]' => 'Test',
            'appbundle_serviceuser[lastName]' => 'OTestyXYZ',
            'appbundle_serviceuser[addressLine1]' => 'Cork Street',
            'appbundle_serviceuser[addressLine2]' => 'The Coombe',
            'appbundle_serviceuser[addressLine3]' => 'Dolphins Barn',
            'appbundle_serviceuser[countyPostcode]' => 1001035,
            'appbundle_serviceuser[eirCode]' => 'D081239',
            'appbundle_serviceuser[landlineTelephone]' => '0123456',
            'appbundle_serviceuser[mobileTelephone]' => '9987654',
            'appbundle_serviceuser[isActive]' => true,
            'appbundle_serviceuser[startDate]' => '2017-04-01',
            'appbundle_serviceuser[finishDate]' => '2017-04-02',
            'appbundle_serviceuser[managingOffice]' => 1
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/OTestyXXX/', $client->getResponse()->getContent());
    }


}
