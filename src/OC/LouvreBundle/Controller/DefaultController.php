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
        return $this->render('@OCLouvre/Default/index.html.twig', ['prices' => $this->getParameter('prices')]);
    }

    /**
     * créer une nouvelle commande
     */
    public function newCommandeAction(Request $request)
    {
        $session = $request->getSession();

        $form = $this->get('form.factory')->create(CommandeType::class, new Commande());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();
            $commande = $this->get('oc_louvre.commande_service')->commande($commande);
            $session->set('commande', $commande);

            // redirige vers la page de paiement
            return $this->redirectToRoute('oc_louvre_stripe_payment', ['commande' => $commande,]);
        }
        return $this->render('@OCLouvre/Billeterie/billeterie.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * enregistre la commande en bdd
     * payer la commande
     * envoyer mail avec la commande
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stripePaymentAction(Request $request)
    {
        $session = $request->getSession();
        $commande = $session->get('commande');

        // recupèration du token
        $token = $request->request->get('stripeToken');
        $this->get('oc_louvre.stripe_payement')->procededPayement($token, $commande);

        //enregistrement commande en base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        // verifie si le paiment et validé
        $paid = $this->get('oc_louvre.stripe_payement')->isPaid();

        // si paiment validé
        if ($paid == true) {
            //marque comme commande payé
            $commande->setPaid(true);
            $em->flush();
            // envoi de la commande par mail
            $this->get('oc_louvre.email_commande')->sendMail($commande);
            // message success validation de commande
            $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');

            return $this->render('@OCLouvre/Default/index.html.twig', ['prices' => $this->getParameter('prices')]);
        }

        return $this->render('@OCLouvre/Stripe/stripe.html.twig',
            [
                'commande' => $commande,
                'listTickets' => $commande->getTickets(),
            ]);
    }

    /**
    //     * envoyer une commande par email
    //     */
//    public function sendMailAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        // recupère la commande $id
//        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);
//        // envoi de la commande par mail
//        $sendmail = $this->get('oc_louvre.email_commande')->sendMail($commande);
//
//        // message success validation de commande
//        $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');
//
//        return $this->render('@OCLouvre/Default/index.html.twig');
//    }
//
//    /**
//     * visualiser le mail d'une commande
//     */
//    public function mailAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);
//        $listTickets = $commande->getTickets();
//
//        return $this->render('@OCLouvre/Email/emailCommande.html.twig',
//            [
//                'listTickets' => $listTickets,
//                'commande' => $commande,
//            ]);
//    }
}