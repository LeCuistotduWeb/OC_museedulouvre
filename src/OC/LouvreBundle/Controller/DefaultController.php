<?php

namespace OC\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OC\LouvreBundle\Entity\Commande;
use OC\LouvreBundle\Form\CommandeType;

use Stripe\Stripe;

class DefaultController extends Controller
{
    /**
     * page principale
     */
    public function indexAction()
    {
//        $calculPrice= $this->get('oc_louvre.calculprice');
        return $this->render('@OCLouvre/Default/index.html.twig', ['prices'=> $this->getParameter('prices')]);
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

            $commande->setCodeReservation($commande->createCodeReserv());

            foreach($commande->getTickets() as $ticket){

                $dateBirthday = $ticket->getVisitor()->getDateBirthday();
                $halfDay = $ticket->getHalfday();
                $reduction = $ticket->getVisitor()->getReduction();

                $ticket->setPrice($this->get('oc_louvre.calculprice')->calculeTicketPrices($dateBirthday));

                if($halfDay == 1){
                    $ticket->setPrice($this->get('oc_louvre.calculprice')->reductionHalfday());
                }
                if($reduction == 1){
                    $ticket->setPrice($this->get('oc_louvre.calculprice')->reductionTicketPrices());
                }
            }
            $em->flush();
            dump($commande);

            // redirige vers la page de payement
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

        return $this->render('@OCLouvre/Email/emailCommande.html.twig',
            [
                'listTickets' => $listTickets,
                'commande' => $commande,
            ]);
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
        $commande = $em->getRepository('OCLouvreBundle:Commande')->find(1);

        //récupère les tickets
        $listTickets = $commande->getTickets();

        // recupèration du token
        $token = $request->request->get('stripeToken');
        // envoi de la commande par mail
        $this->get('oc_louvre.stripe_payement')->procededPayement($token, $commande);

        // envoi de la commande par mail
        $this->get('oc_louvre.email_commande')->sendMail($commande);

        // message success validation de commande
        $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');

        return $this->render('@OCLouvre/Stripe/stripe.html.twig',
            [
                'commande'=>$commande,
                'listTickets'=>$listTickets,
            ]);
    }
}