<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 09/07/2018
 * Time: 10:03
 */

namespace OC\LouvreBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * Class DateHolidayInFrance
 * @package OC\LouvreBundle\Validator
 * @Annotation
 */
class DateHolidayInFrance extends Constraint
{
    public $message = 'Il n\'est pas possible de réserver pour cette date. Le {{ date }} est un jour férié en France. ';
}