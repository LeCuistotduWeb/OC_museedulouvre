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
    public function testIndexIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testSendMailIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/send/2');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}