<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 28/06/2018
 * Time: 13:35
 */

namespace Tests\src\OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function indexIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

//    /**
//     * @test
//     */
//    public function newCommandeIsUp()
//    {
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/order');
//
//        $form = $crawler->selectButton('Valider')->form();
//        $form['oc_louvrebundle_commande[dateVisite]'] = '2018-07-26';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][surname]'] = 'john';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][name]'] = 'Doe';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][dateBirthday]'] = '1991-06-01';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][country]'] = 'FR';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][reduction]'] = 'true';
//        $form['oc_louvrebundle_commande[tickets][0][halfday]'] = 'false';
//        $form['oc_louvrebundle_commande[emailSend][first]'] = 'johndoe@gmail.com';
//        $form['oc_louvrebundle_commande[emailSend][second]'] = 'johndoe@gmail.com';
//        $client->submit($form);
//
//        $crawler = $client->followRedirect(); // Attention à bien récupérer le crawler mis à jour
//        $this->assertSame('/payment', $crawler->g);
//    }

    /**
    * @test
    */
    public function newCommandeIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/order');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function viewMailIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/mail/2');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function sendMailIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/send/2');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function stripePaymentComandeNotCreate()
    {
        $client = static::createClient();

        $client->request('GET', '/payment');

        $this->assertSame(500, $client->getResponse()->getStatusCode());
    }

//    /**
//     * @test
//     */
//    public function sendMailIsUp()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/send/2');
//
//        $this->assertSame(200, $client->getResponse()->getStatusCode());
//    }
}