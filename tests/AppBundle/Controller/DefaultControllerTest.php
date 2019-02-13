<?php

namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    //    public function testIndex()
    //    {
    //        $client = static::createClient();
    //
    //        $crawler = $client->request('GET', '/');
    //
    //        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    //        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    //    }

    public function testEnclosuresAreShownOnHomepage()
    {
        // LiipFunctionalTestBundle provides the ability to load our fixtures in tests.
        $this->loadFixtures([
                LoadBasicParkData::class,
                LoadSecurityData::class,
            ]
        );

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/pathnotexist');
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);

        $crawler = $client->request('GET', '/');
        $this->assertStatusCode(Response::HTTP_OK, $client);


        $table = $crawler->filter('.table-enclosures');

        $this->assertCount(3, $table->filter('tbody tr'));
    }

    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ])->getReferenceRepository();

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }

    public function testItGrowsADinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();

        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        // Get the form using the submit button.
        $form = $crawler->selectButton('Grow dinosaur')->form();

        // fill in the two fields - a select input and a text input
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $client->submit($form);

        $this->assertContains(
            'Grew a large herbivore in enclosure #5',
            $client->getResponse()->getContent()
        );

    }


}
