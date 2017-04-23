<?php

namespace PHPUnit\Framework;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/customer/');
        $crawler = $client->click($crawler->selectLink('Create a new customer')->link());


        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_customer[customerName]' => 'Testy Ltd',
            'appbundle_customer[addressLine1]' => 'Cork Street',
            'appbundle_customer[addressLine2]' => 'The Coombe',
            'appbundle_customer[addressLine3]' => 'Dolphins Barn',
            'appbundle_customer[countyPostcode]' => 1001035,
            'appbundle_customer[eirCode]' => 'D081239',
            'appbundle_customer[landlineTelephone]' => '0123456',
            'appbundle_customer[mobileTelephone]' => '9987654',
            'appbundle_customer[isActive]' => true,
            'appbundle_customer[managingOffice]' => 1,
            'appbundle_customer[mainContact]' => 'Joe Duffy'

        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity

        $crawler = $client->click($crawler->selectLink('Edit This Record')->link());
        $buttonCrawlerNode = $crawler->selectButton('Submit Changes');
        //create a new form object, nb because we can have more than one form, each one is linked to its submit button
        //fill in new form values
        $form = $buttonCrawlerNode->form(array(
            'appbundle_customer[customerName]' => 'Testy LtdXXX',
            'appbundle_customer[addressLine1]' => 'Cork Street',
            'appbundle_customer[addressLine2]' => 'The Coombe',
            'appbundle_customer[addressLine3]' => 'Dolphins Barn',
            'appbundle_customer[countyPostcode]' => 1001035,
            'appbundle_customer[eirCode]' => 'D081239',
            'appbundle_customer[landlineTelephone]' => '0123456',
            'appbundle_customer[mobileTelephone]' => '9987654',
            'appbundle_customer[isActive]' => true,
            'appbundle_customer[managingOffice]' => 1,
            'appbundle_customer[mainContact]' => 'Joe Duffy'

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
