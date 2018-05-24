<?php

namespace OC\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * page principale
     */
    public function indexAction()
    {
        return $this->render('@OCLouvre/Default/index.html.twig');
    }
}
