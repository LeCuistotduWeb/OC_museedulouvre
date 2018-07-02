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
    private $prices;
    private $em;
    private $session;

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
    public function CalculAge(){
        $commandeService = new CommandeService([],1000,$this->em,$this->session,14);
        $birthday = new \DateTime('1991-06-01');
        $dateVisite = new \DateTime('2018-07-14');
        $result = $commandeService->calculeAge( $birthday, $dateVisite);
        $this->assertSame(27, $result);
    }

    /**
     * @test
     */
    public function CalculTicketPriceTypeNormal(){
        $this->prices = [
            'baby' => 0,
            'enfant' => 8,
            'normal' => 16,
            'senior' => 12,
            'reduit' => 10,
            'halfday' => 10,
        ];

        $commandeService = new CommandeService($this->prices, 1000, $this->em, $this->session, 14);

        $birthday = new \DateTime('1991-06-01');
        $dateVisite = new \DateTime('2018-07-14');

        $result = $commandeService->calculeTicketPrices($birthday, $dateVisite);
        $this->assertEquals(16, $result);
    }
}