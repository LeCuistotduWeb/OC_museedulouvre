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
    private $limiHalfDay;
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
     * complete la commande
     * @param $commande
     * @return mixed
     */
    public function checkCommande($commande){

        $commande->setCodeReservation($commande->createCodeReserv());
        foreach ($commande->getTickets() as $ticket) {

            $dateBirthday = $ticket->getVisitor()->getDateBirthday();
            $dateVisite = $commande->getDateVisite();
            $halfDay = $ticket->getHalfday();
            $reduction = $ticket->getVisitor()->getReduction();

            $ticket->setPrice($this->calculeTicketPrices($dateBirthday, $dateVisite));

            if ($halfDay == 1) {
                if($this->hourHalfDay() == 0){
                    $ticket->setPrice($this->reductionHalfday($ticket->getPrice()));
                }else{
                    dump('les billets demi-journée ne sont plus disponnible après 13h');
//                    $this->session->getFlashBag()->add('warning','les billets demi-journée ne sont plus disponnible après 13h');
                }
            }

            if ($reduction == 1) {
                $ticket->setPrice($this->reductionTicketPrices());
            }
        }
        $this->getTicketDispo($commande);
        return $commande;
    }

    /**
     * calcule le prix du billet
     * @param $birthday
     * @param $dateVisite
     * @return mixed
     */
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
        return $price = $this->prices['reduit'];
    }

    /**
     * calcule l'age du visiteur
     * @param \DateTime $birthday
     * @param \DateTime $dateVisite
     * @return int
     */
    public function calculeAge(\DateTime $birthday, \DateTime $dateVisite):int
    {
        return $age = $dateVisite->diff($birthday)->y;
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
     * verifie si moins de 1000 billet vendu
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
            return $this->session->getFlashBag()->add('complet ', 'Il ne reste plus assez de ticket pour cette date.');
        }else{
            return $ticketDispo;
        }
    }

    /**
     * verifie l'heure pour billet demi journée
     * @return bool
     */
    public function hourHalfDay():bool {
        date_default_timezone_set('Europe/Paris');
        $dt = new \DateTime();
        $hour = $dt->format("H");
        $limitHour = $this->limiHalfDay;
        dump($hour > $limitHour);
        return $hour > $limitHour;
    }

//    public function getHolidays(){
//        $year = intval(date('Y'));
//        return $holidays = array(
//            mktime(0, 0, 0, 5,  1,  $year),
//            mktime(0, 0, 0, 11,  1,  $year),
//            mktime(0, 0, 0, 12,  25,  $year),
//            date("l"),
//        );
//    }
}