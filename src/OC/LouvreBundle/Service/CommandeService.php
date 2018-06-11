<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 08/06/2018
 * Time: 14:00
 */

namespace OC\LouvreBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandeService
{
    private $prices;
    private $maxTicketsPerDay;
    private $em;
    private $session;

    public function __construct(array $prices, int $maxTicketsPerDay, EntityManager $em, Session $session)
    {
        $this->prices = $prices;
        $this->maxTicketsPerDay = $maxTicketsPerDay;
        $this->session = $session;
        $this->em = $em;
    }
    public function commande($commande){

        $commande->setCodeReservation($commande->createCodeReserv());
        foreach ($commande->getTickets() as $ticket) {

            $dateBirthday = $ticket->getVisitor()->getDateBirthday();
            $dateVisite = $commande->getDateVisite();
            $halfDay = $ticket->getHalfday();
            $reduction = $ticket->getVisitor()->getReduction();
            $ticket->setPrice($this->calculeTicketPrices($dateBirthday, $dateVisite));

            if ($halfDay == 1) {
                $ticket->setPrice($this->reductionHalfday($ticket->getPrice()));
            }
            if ($reduction == 1) {
                $ticket->setPrice($this->reductionTicketPrices());
            }
        }
        $this->getTicketDispo($commande);
        return $commande;
    }
    public function calculeTicketPrices($birthday, $dateVisite){

        //calcule le nombre d'années entre la date du jour et la date d'anniversaire
        $interval = $this->calculeAge($birthday, $dateVisite);

        if($interval < 4){                              //tarif baby
            return $price = $this->prices['baby'];
        }elseif ($interval > 4 && $interval < 12) {     //tarif enfant
            return $price = $this->prices['enfant'];
        }elseif ($interval > 12 && $interval < 60) {    //tarif normal
            return $price = $this->prices['normal'];
        }elseif ($interval > 60) {                      //tarif senior
            return $price = $this->prices['senior'];
        }
        else {
            return $price = $this->prices['normal'];
        }
    }

    public function reductionTicketPricesPourcent($price){
        $reduction = $price * 0.25;
        $price -=  $reduction;
        return $price;
    }

    public function reductionTicketPrices(){
        return $price = $this->prices['reduit'];
    }

    public function calculeAge(\DateTime $birthday, \DateTime $dateVisite)
    {
        return $age = $dateVisite->diff($birthday)->y;
    }

    /**
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

    public function reductionHalfday($price){
        return $price/2;
    }

    public function getTicketDispo($commande){
        $nbTicketCommande = count($commande->getTickets());
        $repository = $this->em
            ->getRepository('OCLouvreBundle:Commande')
        ;

        $nbTickets = $repository->findBy(
            array('dateVisite' => $commande->getDateVisite())
        );
        $nbTickets = count($nbTickets);
        $ticketDispo = $this->maxTicketsPerDay - $nbTickets - $nbTicketCommande;
        if ($ticketDispo >= 0) {
            return $this->session->getFlashBag()->add('complet ', 'Il ne reste plus assez de ticket pour cette date.');
        }else{
            return $ticketDispo;
        }
    }
}