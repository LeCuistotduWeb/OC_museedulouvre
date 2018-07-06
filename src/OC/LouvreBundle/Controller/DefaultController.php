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
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * page d'accueil
     * @return Response
     * @Route("/", name="oc_louvre_homepage")
     */
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig', [
            'prices' => $this->getParameter('prices'),
            'limitHalfDay' => $this->getParameter('limitHalfDay'),
        ]);
    }

    /**
     * créer une nouvelle commande
     * @Route("/order", name="oc_louvre_new_commande")
     * @param Request $request
     * @param CommandeService $commandeService
     * @return Response
     */
    public function newCommandeAction(Request $request, CommandeService $commandeService): Response
    {
        $session = $request->getSession();
        $commande = ($session->get('commande')) ? $commande = $session->get('commande'): $commande = new Commande();

        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();

            $commandeService->checkCommande($commande);

            if ($commandeService->commandeValid() == true){
                //enregistrement commande en base de données
                $em = $this->getDoctrine()->getManager();
                $em->persist($commande);
                $session->set('commande', $commande);
                $em->flush();
                // redirige vers la page de paiement

                dump($commande);
                return $this->redirectToRoute('oc_louvre_stripe_payment', ['commande' => $commande,]);
            }
            return $this->render('Billeterie/billeterie.html.twig', ['form' => $form->createView(),]);

        }
        return $this->render('Billeterie/billeterie.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * enregistre paie et envoi la commande
     * @Route("/payment", name="oc_louvre_stripe_payment")
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
        dump($commande);
        if($commande != null) {

            // recupèration du token
            $token = $request->request->get('stripeToken');
            $stripePayement->procededPayement($token, $commande);

            // si paiment validé
            if ($stripePayement->isPaid()) {
                //marque comme commande payé
                $em = $this->getDoctrine()->getManager();
                $commande = $em->getRepository('OCLouvreBundle:Commande')->find($session->get('commande')->getId());
                $em->persist($commande);
                $commande->setPaid(true);
                $em->flush();
                // envoi de la commande par mail
                $emailCommande->sendMail($commande);
                // message success validation de commande
                $this->addFlash('success', 'Votre commande est bien enregistrée. Vos billets on été envoyés par email.');
                $session->remove('commande');

                return $this->redirectToRoute('oc_louvre_homepage');
            }

            return $this->render('Stripe/stripe.html.twig',
                [
                    'commande' => $commande,
                    'listTickets' => $commande->getTickets(),
                ]);
        }
        $this->addFlash('danger', 'Vous n\'avez aucune commande de crée.');
        return $this->redirectToRoute('oc_louvre_homepage');
    }

    /**
     * @Route("/cancelCommande", name="oc_louvre_cancel_commande")
     */
    public function cancelCommande(Request $request){
        $session = $request->getSession();
        $session->remove('commande');
        return $this->redirectToRoute('oc_louvre_homepage');
    }

    /**
     * envoyer une commande par email
     * @Route("/send/{id}", name="oc_louvre_mailer_send")
     * @param $id
     * @param EmailCommande $emailCommande
     * @return Response
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

        return $this->render('Default/index.html.twig',
            [
                'prices' => $this->getParameter('prices'),
                'limitHalfDay' => $this->getParameter('limitHalfDay'),
            ]);
    }

    /**
     * test visualiser le mail d'une commande
     * @Route("/mail/{id}", name="oc_louvre_mailer_view")
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
                'prices' => $this->getParameter('prices'),
                'limitHalfDay' => $this->getParameter('limitHalfDay'),
            ]);
    }
}