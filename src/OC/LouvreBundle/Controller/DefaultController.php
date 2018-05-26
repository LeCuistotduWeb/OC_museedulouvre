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
            
            //enregistrement en base de donnée
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            
            // envoi de la commande par mail
            $sendmail = $this->get('oc_louvre.email_commande')->sendMail($commande);
            
            // message success validation de commande
            $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');
            
            // redirige vers la homepage
            return $this->redirectToRoute('oc_louvre_homepage');
            //return $this->redi('@OCLouvre/Default/index.html.twig');
        }

        return $this->render('@OCLouvre/Billeterie/billeterie.html.twig', 
            [
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * envoyer une commande par email
     */
    public function sendMailAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // recupère la commande $id
        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);

        // envoi de la commande par mail
        $sendmail = $this->get('oc_louvre.email_commande')->sendMail($commande);
        
        // message success validation de commande
        $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');

        return $this->render('@OCLouvre/Default/index.html.twig');
    }

    /**
     * visualiser le mail d'une commande
     */
    public function mailAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);
        $listTickets = $commande->getTickets();

        return $this->render('@OCLouvre/Email/emailCommande.html.twig', ['listTickets' => $listTickets]);
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
