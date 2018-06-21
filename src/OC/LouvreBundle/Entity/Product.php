<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 21/06/2018
 * Time: 11:11
 */

namespace OC\LouvreBundle\Entity;


class Product
{
    const FOOD_PRODUCT = 'food';

    private $name;

    private $type;

    private $price;

    public function __construct($name, $type, $price)
    {
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
    }

    public function computeTVA()
    {
        if (self::FOOD_PRODUCT == $this->type) {
            return $this->price * 0.055;
        }

        return $this->price * 0.196;
    }
}