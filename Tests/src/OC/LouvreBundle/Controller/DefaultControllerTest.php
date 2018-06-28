<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 28/06/2018
 * Time: 13:35
 */

namespace Tests\src\OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }


    public function testIndexIsUp()
    {
        $this->client->request('GET', '/');

        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}