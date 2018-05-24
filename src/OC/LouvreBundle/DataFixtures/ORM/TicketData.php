<?php 
namespace OC\LouvreBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use OC\LouvreBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use OC\LouvreBundle\DataFixtures\ORM\VisitorData;
use OC\LouvreBundle\DataFixtures\ORM\CommandeData;

class TicketData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ticket1 = new Ticket();
        $ticket1->setHalfDay(0);
        $ticket1->setPrice(12);
        $ticket1->setCommande($this->getReference('commande1'));
        $ticket1->setVisitor($this->getReference('visitor1'));
        $manager->persist($ticket1);
        
        $ticket2 = new Ticket();
        $ticket2->setVisitor($this->getReference('visitor3'));
        $ticket2->setCommande($this->getReference('commande1'));
        $ticket2->setHalfDay(0);
        $ticket2->setPrice(10);
        $manager->persist($ticket2);
        
        $ticket3 = new Ticket();
        $ticket3->setVisitor($this->getReference('visitor2'));
        $ticket3->setCommande($this->getReference('commande1'));
        $ticket3->setHalfDay(0);
        $ticket3->setPrice(16);
        $manager->persist($ticket3);
        
        $ticket4 = new Ticket();
        $ticket4->setVisitor($this->getReference('visitor2'));
        $ticket4->setCommande($this->getReference('commande2'));
        $ticket4->setHalfDay(0);
        $ticket4->setPrice(16);
        $manager->persist($ticket4);

        $manager->flush();

        $this->addReference('ticket1', $ticket1);
        $this->addReference('ticket2', $ticket2);
        $this->addReference('ticket4', $ticket4);
    }

    public function getDependencies()
    {
        return array(
            CommandeData::class,
            VisitorData::class,
        );
    }
}