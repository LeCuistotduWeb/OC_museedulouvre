<?php

namespace OC\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * page principale
     */
    public function indexAction()
    {
        return $this->render('@OCLouvre/Default/index.html.twig');
    }

    public function testServiceAction()
    {
        //test du service permettant de choisir le tgarif du billet en fonction de la date anniversaire
        $dateBirthday = '1991-06-01';
        $price = $this->get('oc_louvre.calculprice')->calculeTicketPrices($dateBirthday);
        
        dump($price);

        return $this->render('@OCLouvre/Default/index.html.twig');
    }
}
