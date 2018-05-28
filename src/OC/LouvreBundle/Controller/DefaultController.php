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
            dump($commande);
            //enregistrement en base de donnée
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            
            // envoi de la commande par mail
            $this->get('oc_louvre.email_commande')->sendMail($commande);
            
            // message success validation de commande
            $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');
            
            // redirige vers la homepage
            return $this->redirectToRoute('oc_louvre_stripe_payment', 
                [
                    'commande' => $commande,
                ]);
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stripePaymentAction(Request $request)
    {   
        // recuperer la commande
        $em = $this->getDoctrine()->getManager();
        // recupère la commande $id
        $commande = $em->getRepository('OCLouvreBundle:Commande')->find(5);

        \Stripe\Stripe::setApiKey("sk_test_GvS4ZcdJiZ22G4hXTjbmaHW1");

        $charge = \Stripe\Charge::create(array(
        "amount" => $commande->getPriceTotal()*100,
        "currency" => "eur",
        "source" => "tok_amex", // obtained with Stripe.js
        "description" => "paiment commande code : ". $commande->getCodeReservation(),
        ));

        return $this->render('@OCLouvre/Stripe/stripe.html.twig', ['commande'=>$commande]);
    }
}