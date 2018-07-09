<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 09/07/2018
 * Time: 09:09
 */

namespace OC\LouvreBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Dayclose
 * @Annotation
 * @package OC\LouvreBundle\Validator
 */
class Dayclose extends Constraint
{
    public $message = "Il n'est pas possible de réserver des billets les mardis et dimanches.";
}