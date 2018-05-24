<?php 
namespace OC\LouvreBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use OC\LouvreBundle\Entity\Commande;

class CommandeData extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        $commande1 = new Commande();
        $commande1->setEmailSend('gaetan.boyron@gmail.com');
        $commande1->setDateVisite(new \DateTime('2018/06/01'));
        $commande1->setCodeReservation('ds654d565');
        $commande1->setPriceTotal(24);
        $manager->persist($commande1);

        $commande2 = new Commande();
        $commande2->setEmailSend('gaetan.boyron@gmail.com');
        $commande2->setDateVisite(new \DateTime('2018/10/21'));
        $commande2->setCodeReservation('654sdfff');
        $commande2->setPriceTotal(24);
        
        $manager->persist($commande2);

        $manager->flush();

        $this->addReference('commande1', $commande1);
        $this->addReference('commande2', $commande2);
    }
}