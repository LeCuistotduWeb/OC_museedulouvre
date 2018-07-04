<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 08/06/2018
 * Time: 14:00
 */

namespace Tests\OC\LouvreBundle\Service;

use OC\LouvreBundle\Service\CommandeService;
use PHPUnit\Framework\TestCase;

class CommandeServiceTest extends TestCase
{
    private $em;
    private $session;
    private $prices = [
        'baby' => 0,
        'enfant' => 8,
        'normal' => 16,
        'senior' => 12,
        'reduit' => 10,
        'halfday' => 10,
    ];

    public function setUp()
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                    ->disableOriginalConstructor()
                    ->setMethods(['get'])
                    ->getMock();

        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
    }

    /**
     * @test
     */
    public function calculAge(){
        $commandeService = new CommandeService($this->prices,1000,$this->em,$this->session,14);
        $birthday = new \DateTime('1991-06-01');
        $dateVisite = new \DateTime('2018-07-14');
        $result = $commandeService->calculeAge( $birthday, $dateVisite);
        $this->assertSame(27, $result);
    }

    /**
     * @test
     */
    public function calculReductionTicketPrices(){
        $commandeService = new CommandeService($this->prices,1000,$this->em,$this->session,14);
        $result = $commandeService->reductionTicketPrices();
        $this->assertSame(10, $result);
    }

    /**
     * @test
     */
    public function calculReductionTicketPricesPourcent(){
        $commandeService = new CommandeService($this->prices,1000,$this->em,$this->session,14);
        $price = 16;
        $result = $commandeService->reductionTicketPricesPourcent($price);
        $this->assertEquals(8.0, $result);
    }

//    /**
//     * @test
//     */
//    public function commandeValidIsTrue(){
//        $commandeService = new CommandeService($this->prices,1000,$this->em,$this->session,14);
//        $messError = count($this->session->getFlashBag()->peekAll());
//        $commandeService->commandeValid();
//        $this->assertSame(true, $messError);
//    }

    /**
     * @test
     */
    public function reductionHalfday(){
        $commandeService = new CommandeService($this->prices,1000,$this->em,$this->session,14);
        $result = $commandeService->reductionHalfday(16);
        $this->assertEquals(8, $result);
    }

    /**
     * @test
     */
    public function calculTicketPriceTypeNormal(){
        $commandeService = new CommandeService($this->prices, 1000, $this->em, $this->session, 14);

        $birthday = new \DateTime('1991-06-01');
        $dateVisite = new \DateTime('2018-07-14');

        $result = $commandeService->calculeTicketPrices($birthday, $dateVisite);
        $this->assertEquals(16, $result);
    }

    /**
     * @test
     */
    public function calculTicketPriceTypeSenior(){
        $commandeService = new CommandeService($this->prices, 1000, $this->em, $this->session, 14);

        $birthday = new \DateTime('1953-10-14');
        $dateVisite = new \DateTime('2018-07-14');

        $result = $commandeService->calculeTicketPrices($birthday, $dateVisite);
        $this->assertEquals(12, $result);
    }

    /**
     * @test
     */
    public function calculTicketPriceTypeBaby(){
        $commandeService = new CommandeService($this->prices, 1000, $this->em, $this->session, 14);

        $birthday = new \DateTime('2016-10-14');
        $dateVisite = new \DateTime('2018-07-14');

        $result = $commandeService->calculeTicketPrices($birthday, $dateVisite);
        $this->assertEquals(0, $result);
    }

    /**
     * @test
     */
    public function calculTicketPriceTypeEnfant(){
        $commandeService = new CommandeService($this->prices, 1000, $this->em, $this->session, 14);

        $birthday = new \DateTime('2010-02-20');
        $dateVisite = new \DateTime('2018-07-14');

        $result = $commandeService->calculeTicketPrices($birthday, $dateVisite);
        $this->assertEquals(8, $result);
    }
}
