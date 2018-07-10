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
    /**
     * @test
     */
    public function indexIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function newCommandeIsUp(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/order');
        $this->assertEquals(1, $crawler->filter('h3:contains("Billeterie")')->count());
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function paymentIsUp(){
        $client = static::createClient();
        $client->request('GET', '/payment');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function cancelCommandeIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/cancelCommande');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

//    /**
//     * @test
//     */
//    public function newCommandeTestBilleterie(){
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/order');
//        //Sélection basée sur la valeur, l'id ou le nom des boutons
//        $form = $crawler->selectButton('submit')->form();
//
//        $form['oc_louvrebundle_commande[dateVisite]'] = '2018-07-26';
//
//        $form['oc_louvrebundle_commande[tickets][0][visitor][surname]'] = 'boyron';
//        $form['oc_louvrebundle_commande[tickets][0][visitor][name]'] = 'Gaetan';
//
//        $form['oc_louvrebundle_commande[tickets][0][visitor][dateBirthday][day]']->select('01');
//        $form['oc_louvrebundle_commande[tickets][0][visitor][dateBirthday][month]']->select('06');
//        $form['oc_louvrebundle_commande[tickets][0][visitor][dateBirthday][year]']->select('1991');
//
//        $form['oc_louvrebundle_commande[tickets][0][visitor][country]']->select('France');
//
//        $form['oc_louvrebundle_commande[tickets][0][visitor][reduction]']->tick();
//        $form['oc_louvrebundle_commande[tickets][0][halfDay]']->untick();
//
//        $form['oc_louvrebundle_commande[emailSend][first]'] = 'gaetan.boyron@gmail.com';
//        $form['oc_louvrebundle_commande[emailSend][second]'] = 'gaetan.boyron@gmail.com';
//
//        $client->submit($form);
//        $crawler = $client->followRedirect();
//        var_dump($client->getResponse()->getContent());
//        $this->assertEquals(0, $crawler->filter('.alert')->count());
//    }
}