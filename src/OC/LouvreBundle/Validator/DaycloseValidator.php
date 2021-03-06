<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 09/07/2018
 * Time: 09:18
 */

namespace OC\LouvreBundle\Validator;

use OC\LouvreBundle\Service\CommandeService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DaycloseValidator extends ConstraintValidator
{
    protected $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    public function validate($value, Constraint $constraint){
        if($this->commandeService->dayClose($value)){
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ date }}", $value->format("d/m/y"))
                ->addViolation();
        }
    }
}