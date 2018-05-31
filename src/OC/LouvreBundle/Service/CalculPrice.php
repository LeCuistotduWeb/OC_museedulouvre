<?php
namespace OC\LouvreBundle\Service;


class CalculPrice
{
    public function calculeTicketPrices($birthday){

        //calcule le nombre d'années entre la date du jour et la date d'anniversaire
        $interval = $this->calculeAge($birthday);

        if($interval < 4){  //tarif baby
            return $price = 0.00;
        }elseif ($interval > 4 && $interval < 12) { //tarif enfant
            return $price = 8.00;
        }elseif ($interval > 12 && $interval < 60) {    //tarif normal
            return $price = 16.00;
        }elseif ($interval > 60) {  //tarif senior
            return $price = 12.00;
        }
        else {
            return $price = 16.00;
        }
    }

    public function reductionTicketPricesPourcent($price){
        $reduction = $price * 0.25;
        $price -=  $reduction;
        return $price;
    }

    public function reductionTicketPrices(){
        return $price = 10.00;
    }

//    public function reductionHalfday($price){
//        return $price/2;
//    }

    public function calculeAge($birthday)
    {
        $today = new \DateTime();
        $age = $today->diff($birthday);
        return  $age->format('%y');
    }
}