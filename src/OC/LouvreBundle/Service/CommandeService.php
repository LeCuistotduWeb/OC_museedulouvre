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
    private $em;
    private $session;
    private $today;

    /**
     * CommandeService constructor.
     * @param array $prices
     * @param int $maxTicketsPerDay
     * @param EntityManager $em
     * @param Session $session
     */
    public function __construct(array $prices, int $maxTicketsPerDay, EntityManager $em, Session $session, string $limitHalfDay)
    {
        $this->prices = $prices;
        $this->maxTicketsPerDay = $maxTicketsPerDay;
        $this->session = $session;
        $this->limiHalfDay = $limitHalfDay;
        $this->em = $em;
        $this->today = new \DateTime();
    }

    /**
     * @param $commande
     */
    public function checkCommande($commande){

        $commande->setCodeReservation($commande->createCodeReserv());

        // si nombre de ticket = 0
        if(count($commande->getTickets()) <= 0){
            $this->session->getFlashBag()->add('danger ', 'Veuillez Ajouter un Billet à votre commande.');
        }
        foreach ($commande->getTickets() as $ticket) {

            $dateBirthday = $ticket->getVisitor()->getDateBirthday();
            $dateVisite = $commande->getDateVisite();
            $halfDay = $ticket->getHalfday();
            $reduction = $ticket->getVisitor()->getReduction();

            // calcul le prix du billet en fonction de la date de visite et de la date aniversaire du client
            $ticket->setPrice($this->calculeTicketPrices($dateBirthday, $dateVisite));

            if ($halfDay == 1) {
                $ticket->setPrice($this->reductionHalfday($ticket->getPrice()));
            }

            // si jour de visite est aujourd'hui && heure dachat superieur a heure limit && demmande billet journée;
            if (($this->dateVisiteIsDateToday($dateVisite) == true) && ($this->hourHalfDay() == 1) && ($halfDay == 0)) {
                $this->session->getFlashBag()->add('danger', 'L\'achat d\'un billet journée pour le jour même n\'est plus disponnible après ' . $this->limiHalfDay . 'h.');
            }

            // si jour de visite est un jour de fermeture
            if (($this->verifyIfDateIsClose($dateVisite) == 1) || ($this->dayClose($dateVisite) == 1)) {
                $this->session->getFlashBag()->add('danger ', 'Le musée est fermé tout les mardi, les 1er mai, 1er novembre et 25 décembre.. Veuillez choisir une autre date de visite.');
            }

            //si billet avec reduction
            if ($reduction == 1) {
                $ticket->setPrice($this->reductionTicketPrices());
            }
        }

        // verifie si il reste assez de billet disponible
        if ($this->getTicketDispo($commande) == 0) {
            $this->session->getFlashBag()->add('danger ', 'Il ne reste plus assez de ticket pour cette date.');
        }
    }

    public function commandeValid(){
        if ($this->session->getFlashBag()->peekAll() == null){
            return true;
        }else{
            return false;
        }
    }

    /**
     * calcule le prix du billet
     * @param $birthday
     * @param $dateVisite
     * @return mixed
     */
    public function calculeTicketPrices($birthday, $dateVisite){

        $interval = $this->calculeAge($birthday, $dateVisite);

        if($interval < 4){                              //tarif baby
            return $this->prices['baby'];
        }elseif ($interval > 4 && $interval < 12) {     //tarif enfant
            return $this->prices['enfant'];
        }elseif ($interval > 12 && $interval < 60) {    //tarif normal
            return $this->prices['normal'];
        }elseif ($interval > 60) {                      //tarif senior
            return $this->prices['senior'];
        }
        else {
            return $this->prices['normal'];
        }
    }

    /**
     * calcul une reduction avec un pourcentage
     * @param $price
     * @return float
     */
    public function reductionTicketPricesPourcent($price):float {
        $reduction = $price * 0.25;
        $price -=  $reduction;
        return $price;
    }

    /**
     * retourne le prix d'un ticket avec reduction
     * @return mixed
     */
    public function reductionTicketPrices(){
        return $this->prices['reduit'];
    }

    /**
     * calcule l'age du visiteur
     * @param \DateTime $birthday
     * @param \DateTime $dateVisite
     * @return int
     */
    public function calculeAge(\DateTime $birthday, \DateTime $dateVisite):int
    {
        return $dateVisite->diff($birthday)->y;
    }

    /**
     * recupere le prix
     * @return array
     */
    public function getPrices():array
    {
        return $this->prices;
    }

    /**
     * reduction demi-journée
     * @param $price
     * @return float|int
     */
    public function reductionHalfday($price):float {
        return $price/2;
    }

    /**
     * verifie si moins de $limitTickets billet vendu
     * @param $commande
     * @return int|void
     */
    public function getTicketDispo($commande){
        $nbTicketCommande = count($commande->getTickets());
        $repository = $this->em->getRepository('OCLouvreBundle:Commande');

        $nbTickets = $repository->findBy(
            array('dateVisite' => $commande->getDateVisite())
        );
        $nbTickets = count($nbTickets);
        $ticketDispo = $this->maxTicketsPerDay - $nbTickets - $nbTicketCommande;
        if ($ticketDispo <= 0) {
            return false;
        }else{
            return true;
        }
    }

    /**
     * verifie l'heure pour billet journée
     * @return bool
     */
    public function hourHalfDay():bool {
        date_default_timezone_set('Europe/Paris');
        $dt = new \DateTime();
        $hour = $dt->format("H");
        $limitHour = $this->limiHalfDay;
        return $hour > $limitHour;
    }

    public function dateVisiteIsDateToday($dateVisite){
        $today = $this->today->format('Y-m-d');
        $dateVisite = $dateVisite->format('Y-m-d');
        $result = $dateVisite == $today;
        if($result == true){
            return true;
        }
        return false;
    }

    public function dayClose($dateVisite){
        $dayClose = "Tuesday";
        if (date_format($dateVisite, 'l') == $dayClose){
            return true ;
        }
        return false;
    }

    /**
     * @param $dateVisite
     * @return bool
     */
    public function verifyIfDateIsClose($dateVisite){
        $year = date('Y');
        $dayClose = [
            new \DateTime($year.'-05-01'),
            new \DateTime($year.'-12-25'),
            new \DateTime($year.'-11-01'),
        ];

        foreach ($dayClose as $day){
            if($dateVisite->format('m-d') == $day->format('m-d')){
                return true;
            }
        }
        return null;
    }
}