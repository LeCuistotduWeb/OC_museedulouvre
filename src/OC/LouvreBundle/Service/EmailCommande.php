<?php

namespace OC\LouvreBundle\Service;
use Symfony\Component\HttpFoundation\Response;

class EmailCommande extends \Twig_Extension
{
    private $mailer;
    private $templating;

    /**
     * EmailCommande constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }

    /**
     * envoi la commande par mail
     * @param $commande
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMail($commande)
    {
        // recupère les tickets de la commande $id
        $listTickets = $commande->getTickets();
        // mail
        $mail = (new \Swift_Message())
            ->setSubject('Vos Billets - Musee du LOUVRE')
            ->setFrom(['contact@gaetanboyron.fr' => 'Musée du Louvre | Billeterie'])
            ->setTo($commande->getEmailSend())
            ->setBody($this->templating->render('Email/emailCommande.html.twig',
                [
                    'listTickets' => $listTickets,
                    'commande' => $commande,
                ]))
            ->setcharset('utf-8')
            ->setContentType("text/html");

        // envoi du mail
        $this->mailer->send($mail);
    }

//    /**
//     * afficher un mail.
//     * @param $commande
//     * @return string
//     * @throws \Twig_Error_Loader
//     * @throws \Twig_Error_Runtime
//     * @throws \Twig_Error_Syntax
//     */
//    public function viewMail($commande)
//    {
//        // recupère les tickets de la commande $id
//        $listTickets = $commande->getTickets();
//
//        return $this->templating->render('Email/emailCommande.html.twig', ['listTickets' => $listTickets]);
//    }
}
