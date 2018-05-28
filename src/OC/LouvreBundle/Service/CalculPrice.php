<?php
namespace OC\LouvreBundle\Service;


class CalculPrice
{
    public function calculeTicketPrices($birthday){
        $aujourdhui = date('Y-m-d');
        //calcule le nombre d'années entre la date du jour et la date d'anniversaire
        $interval = $this->calculAge($birthday);

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

//    public function reductionTicketPricesPourcent($price){
//        $reduction = $price * 0.25;
//        $price -=  $reduction;
//        return $price;
//    }

    public function reductionTicketPrices(){
        return $price = 10.00;
    }

    public function test(){
        return 'ca fonctionne bien';
    }

    public function calculAge($dateBirthday)
    {
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime($dateBirthday);
        $age = $datetime1->diff($datetime2);
        return  $age->format('%y');
    }
}