<?php

namespace PHPUnit\Framework;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class OfficeControllerTest extends WebTestCase
{

    //this test tests that the View link to second item (id=2) in the index view is routes correctly
    public function testShowSecondOfficeItemfromIndex()
    {

        // this link is available to users who are not authenticated
        $client = static::createClient();


        $client->followRedirects(true);
        $crawler = $client->request('GET', '/office');
        $link= $crawler->selectLink('View')
            ->eq(1)
            ->link();

        $this->assertEquals(

            $link->getUri(),'http://localhost/office/2');

}

    //this test tests that the Create a New Office link in the index view of offices is correct

    public function testShowNewOffice()
    {
        // this link is only available to authenticated users with the ROLE_ADMIN role

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office');
        $link= $crawler->selectLink('Create a new Office')
            ->eq(0)
            ->link();

        $this->assertEquals(

            $link->getUri(),'http://localhost/office/new');

    }

    //this test tests that the Edit Office link in the show view of an office item is correct

    public function testShowOfficeIndex()
    {
        // this link is only available to authenticated users with the ROLE_ADMIN role

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office/2');
        $link= $crawler->selectLink('Edit')
            ->eq(0)
            ->link();

        $this->assertEquals(

            $link->getUri(),'http://localhost/office/2/edit');

    }

    //this test tests that the Back to the Office link in the show view of an office item is correct

    public function testShowOfficeIndexFromView()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office/2');
        $link= $crawler->selectLink('Back to the Offices list')
            ->link();

        $this->assertEquals(

            $link->getUri(),'http://localhost/office/');

    }

    public function testShowOfficeIndexFromEditView()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office/2/edit');
        $link= $crawler->selectLink('Back to the Offices list')
            ->link();

        $this->assertEquals(

            $link->getUri(),'http://localhost/office/');

    }

    public function testEditFormRedirect()
    {
        // this link is only available to authenticated users with the ROLE_ADMIN role

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office/2');
        $link = $crawler->selectLink('Edit')->link();
// this time actually follow the link
        $client->click($link);
// Check that we're now on the edit page

        $this->assertEquals(

            $client->getRequest()->getUri(),'http://localhost/office/2/edit');


    }

    public function testEditFormSubmit()
    {
        // this link is only available to authenticated users with the ROLE_ADMIN role

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET','/office/1');
        // follow the Edit link
        $crawler = $client->click($crawler->selectLink('Edit')->link());
        $buttonCrawlerNode = $crawler->selectButton('Submit Changes');
        //create a new form object, nb because we can have more than one form, each one is linked to its submit button text value
        //fill in new form values
             $form = $buttonCrawlerNode->form(array(
        'appbundle_office[officeName]'=> 'Clane',
        'appbundle_office[addressLine1]' => 'Ballinagappa Road',
        'appbundle_office[addressLine2]' => 'Clane',
        'appbundle_office[addressLine3]'=> 'Kildare',
        'appbundle_office[eirCode]' => 'D05W987',
        'appbundle_office[landlineTelephone]' => '04556555',
        'appbundle_office[mobileTelephone]' => '12456',
        'appbundle_office[isActive]' => true,
        'appbundle_office[countyPostcode]' => 1001009));
        // submit the form
        $client->submit($form);
        $client->followRedirects(true);
        // check that we're back on the show page
        $this->assertEquals(

            $client->getRequest()->getUri(),'http://localhost/office/1');

    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'testuser@gmail.com',
            'PHP_AUTH_PW'   => 'pa$$word',
        ));

            // Create a new entry in the database
        $crawler = $client->request('GET', '/office/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /office/");
        $crawler = $client->click($crawler->selectLink('Create a new Office')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_office[officeName]'=> 'Kerry',
            'appbundle_office[addressLine1]' => 'Main Street',
            'appbundle_office[addressLine2]' => 'Dingle',
            'appbundle_office[addressLine3]'=> '',
            'appbundle_office[eirCode]' => 'K05W987',
            'appbundle_office[landlineTelephone]' => '0123456',
            'appbundle_office[mobileTelephone]' => '9987654',
            'appbundle_office[isActive]' => true,
            'appbundle_office[countyPostcode]' => 1001008));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Kerry")')->count(), 'Missing element td:contains("Kerry")');

        $crawler = $client->click($crawler->selectLink('Edit')->link());
        $buttonCrawlerNode = $crawler->selectButton('Submit Changes');
        //create a new form object, nb because we can have more than one form, each one is linked to its submit button
        //fill in new form values
        $form = $buttonCrawlerNode->form(array(
            'appbundle_office[officeName]'=> 'Kerry',
            'appbundle_office[addressLine1]' => 'Main Street',
            'appbundle_office[addressLine2]' => 'DingleXYZ',
            'appbundle_office[addressLine3]'=> '',
            'appbundle_office[eirCode]' => 'K05W987',
            'appbundle_office[landlineTelephone]' => '0123456',
            'appbundle_office[mobileTelephone]' => '9987654',
            'appbundle_office[isActive]' => true,
            'appbundle_office[countyPostcode]' => 1001008));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/DingleXYZ/', $client->getResponse()->getContent());


    }


}