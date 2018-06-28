<?php

namespace OC\LouvreBundle\Controller;

use OC\LouvreBundle\Service\CommandeService;
use OC\LouvreBundle\Service\EmailCommande;
use OC\LouvreBundle\Service\StripePayement;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OC\LouvreBundle\Entity\Commande;
use OC\LouvreBundle\Form\CommandeType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * page principale
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, CommandeService $commandeService): Response
    {
        return $this->render('default/index.html.twig', [
            'prices' => $this->getParameter('prices'),
            'limitHalfDay' => $this->getParameter('limitHalfDay'),
        ]);
    }

    /**
     * créer une nouvelle commande
     * @param Request $request
     * @param CommandeService $commandeService
     * @return Response
     */
    public function newCommandeAction(Request $request, CommandeService $commandeService): Response
    {
        $session = $request->getSession();
        
        $form = $this->get('form.factory')->create(CommandeType::class, new Commande());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();

            $commandeService->checkCommande($commande);

            if ($commandeService->commandeValid() == true){
                $session->set('commande', $commande);
                // redirige vers la page de paiement
                return $this->redirectToRoute('oc_louvre_stripe_payment', ['commande' => $commande,]);
            }else{
                return $this->render('Billeterie/billeterie.html.twig', ['form' => $form->createView(),]);
            }

        }
        return $this->render('Billeterie/billeterie.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * enregistre paie et envoi la commande
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function stripePaymentAction(Request $request, StripePayement $stripePayement, EmailCommande $emailCommande): Response
    {
        $session = $request->getSession();
        $commande = $session->get('commande');

        if($commande != null) {
            // recupèration du token
            $token = $request->request->get('stripeToken');
            $stripePayement->procededPayement($token, $commande);

            //enregistrement commande en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            // si paiment validé
            if ($stripePayement->isPaid()) {
                //marque comme commande payé
                $commande->setPaid(true);
                $em->flush();
                // envoi de la commande par mail
                $emailCommande->sendMail($commande);
                // message success validation de commande
                $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');
                $session->remove('commande');

                return $this->redirectToRoute('oc_louvre_homepage', [
                    'prices' => $this->getParameter('prices'),
                    'limitHalfDay' => $this->getParameter('limitHalfDay'),
                ]);
            }

            return $this->render('Stripe/stripe.html.twig',
                [
                    'commande' => $commande,
                    'listTickets' => $commande->getTickets(),
                ]);
        }
        throw new NotFoundHttpException('Erreur : Aucune commande n\'a été créée.');
    }

    /**
     * test envoyer une commande par email
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMailAction($id, EmailCommande $emailCommande)
    {
        $em = $this->getDoctrine()->getManager();

        // recupère la commande $id
        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);
        // envoi de la commande par mail
        $emailCommande->sendMail($commande);

        // message success validation de commande
        $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');

        return $this->render('Default/index.html.twig', ['prices' => $this->getParameter('prices')]);
    }

    /**
     * test visualiser le mail d'une commande
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $commande = $em->getRepository('OCLouvreBundle:Commande')->find($id);
        $listTickets = $commande->getTickets();

        return $this->render('Email/emailCommande.html.twig',
            [
                'listTickets' => $listTickets,
                'commande' => $commande,
            ]);
    }
}