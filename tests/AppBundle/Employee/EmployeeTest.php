<?php
namespace PHPUnit\Framework;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW' => 'pa$$word',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/employee/');
        $crawler = $client->click($crawler->selectLink('Create a new Employee')->link());


        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_employee[firstName]' => 'Test',
            'appbundle_employee[lastName]' => 'OTesty',
            'appbundle_employee[staffNumber]' => 'IWA06549',
            'appbundle_employee[addressLine1]' => 'Cork Street',
            'appbundle_employee[addressLine2]' => 'The Coombe',
            'appbundle_employee[addressLine3]' => 'Dolphins Barn',
            'appbundle_employee[countyPostcode]' => 1001035,
            'appbundle_employee[eirCode]' => 'D081239',
            'appbundle_employee[landlineTelephone]' => '0123456',
            'appbundle_employee[mobileTelephone]' => '9987654',
            'appbundle_employee[isActive]' => true,
            'appbundle_employee[startDate]' => '2017-04-01',
            'appbundle_employee[finishDate]' => '2017-04-02',
            'appbundle_employee[managingOffice]' => 1
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity

        $crawler = $client->click($crawler->selectLink('Edit Record')->link());
        $buttonCrawlerNode = $crawler->selectButton('Submit Changes');
        //create a new form object, nb because we can have more than one form, each one is linked to its submit button
        //fill in new form values
        $form = $buttonCrawlerNode->form(array(
            'appbundle_employee[firstName]' => 'Test',
            'appbundle_employee[lastName]' => 'OTestyXXX',
            'appbundle_employee[staffNumber]' => 'IWA06549',
            'appbundle_employee[addressLine1]' => 'Cork Street',
            'appbundle_employee[addressLine2]' => 'The Coombe',
            'appbundle_employee[addressLine3]' => 'Dolphins Barn',
            'appbundle_employee[countyPostcode]' => 1001035,
            'appbundle_employee[eirCode]' => 'D081239',
            'appbundle_employee[landlineTelephone]' => '0123456',
            'appbundle_employee[mobileTelephone]' => '9987654',
            'appbundle_employee[isActive]' => true,
            'appbundle_employee[startDate]' => '2017-04-01',
            'appbundle_employee[finishDate]' => '2017-04-02',
            'appbundle_employee[managingOffice]' => 1
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
