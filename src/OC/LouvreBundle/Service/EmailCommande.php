<?php

namespace OC\LouvreBundle\Service;
use Symfony\Component\HttpFoundation\Response;

class EmailCommande extends \Twig_Extension
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }

    public function sendMail($commande)
    {   
        // recupère les tickets de la commande $id
        $listTickets = $commande->getTickets();
        // mail
        $mail = (new \Swift_Message())
            ->setSubject('Vos Billets - Musee du LOUVRE')
            ->setFrom('contact@louvre.fr')
            ->setTo($commande->getEmailSend())
            ->setBody($this->templating->render('@OCLouvre/Email/emailCommande.html.twig', ['listTickets' => $listTickets]))
            ->setcharset('utf-8')
            ->setContentType("text/html");

        // envoi du mail
        return $this->mailer->send($mail);
    }
    public function viewMail($commande)
    {   
        // recupère les tickets de la commande $id
        $listTickets = $commande->getTickets();

        return $this->templating->render('@OCLouvre/Email/emailCommande.html.twig', ['listTickets' => $listTickets]);
    }
}