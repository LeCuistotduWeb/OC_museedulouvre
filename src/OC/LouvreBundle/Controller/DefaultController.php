<?php

namespace OC\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OC\LouvreBundle\Entity\Commande;
use OC\LouvreBundle\Form\CommandeType;

class DefaultController extends Controller
{
    /**
     * page principale
     */
    public function indexAction()
    {
        return $this->render('@OCLouvre/Default/index.html.twig');
    }

    /**
     * créer une nouvelle commande
     */
    public function newCommandeAction(Request $request)
    {

        $form = $this->get('form.factory')->create(CommandeType::class, new Commande());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $commande = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            $em->flush();

            return $this->render('@OCLouvre/Default/index.html.twig');
        }

        return $this->render('@OCLouvre/Billeterie/billeterie.html.twig', [
            'form' => $form->createView(),
        ]

        );
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
