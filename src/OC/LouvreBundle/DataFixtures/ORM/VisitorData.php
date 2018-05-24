<?php 
namespace OC\LouvreBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use OC\LouvreBundle\Entity\Visitor;

class VisitorData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $visitor1 = new Visitor();
        $visitor1->setName('Gaetan');
        $visitor1->setSurname('Boyron');
        $visitor1->setDateBirthday(new \DateTime('1991/06/01'));
        $visitor1->setReduction(1);
        $visitor1->setCountry('France');
        $manager->persist($visitor1);
        
        $visitor2 = new Visitor();
        $visitor2->setName('John');
        $visitor2->setSurname('Doe');
        $visitor2->setDateBirthday(new \DateTime('1997/03/14'));
        $visitor2->setReduction(0);
        $visitor2->setCountry('France');
        $manager->persist($visitor2);
        
        $visitor3 = new Visitor();
        $visitor3->setName('Alice');
        $visitor3->setSurname('Corta');
        $visitor3->setDateBirthday(new \DateTime('1956/12/26'));
        $visitor3->setReduction(0);
        $visitor3->setCountry('Espagne');
        $manager->persist($visitor3);

        $manager->flush();

        $this->addReference('visitor1', $visitor1);
        $this->addReference('visitor2', $visitor2);
        $this->addReference('visitor3', $visitor3);
    }
}