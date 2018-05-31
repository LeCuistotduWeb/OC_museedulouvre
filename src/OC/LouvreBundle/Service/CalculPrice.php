<?php
namespace OC\LouvreBundle\Service;


class CalculPrice
{

    private $prices;

    public function __construct(array $prices)
    {
        $this->prices = $prices;
    }

    public function calculeTicketPrices($birthday){

        //calcule le nombre d'années entre la date du jour et la date d'anniversaire
        $interval = $this->calculeAge($birthday);

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

//    public function reductionHalfday($price){
//        return $price/2;
//    }

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
}