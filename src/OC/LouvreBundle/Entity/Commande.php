<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\CommandeRepository")
 *
 */
class Commande
{
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->codeReservation = $this->dateVisite.'_'.$this->id;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="date")
     * @Assert\Date()
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="emailSend", type="string", length=255)
     * @Assert\Email()
     */
    private $emailSend;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $codeReservation;

    /**
     * @var bool
     *
     * @ORM\Column(name="paid", type="boolean")
     * @Assert\Type("bool")
     */
    private $paid = false;

    /**
     * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Ticket", mappedBy="commande", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
    */
    private $tickets;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Commande
     */
    public function setDateVisite($dateVisite)
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * Get dateVisite
     *
     * @return \DateTime
     */
    public function getDateVisite()
    {
        return $this->dateVisite;
    }

    /**
     * Set emailSend
     *
     * @param string $emailSend
     *
     * @return Commande
     */
    public function setEmailSend($emailSend)
    {
        $this->emailSend = $emailSend;

        return $this;
    }

    /**
     * Get emailSend
     *
     * @return string
     */
    public function getEmailSend()
    {
        return $this->emailSend;
    }

    /**
     * Set codeReservation
     *
     * @param string $codeReservation
     *
     * @return Commande
     */
    public function setCodeReservation($codeReservation)
    {
        $this->codeReservation = $codeReservation;
        return $this;
    }

    /**
     * Get codeReservation
     *
     * @return string
     */
    public function getCodeReservation()
    {
        return $this->codeReservation;
    }

    /**
     * Add ticket
     *
     * @param \OC\LouvreBundle\Entity\Ticket $ticket
     *
     * @return Commande
     */
    public function addTicket(\OC\LouvreBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        // On lie la commande au tickets
        $ticket->setCommande($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \OC\LouvreBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\OC\LouvreBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }


    public function getPriceTotal(){
        $priceTotal = 0;
        foreach($this->getTickets() as $ticket ){
            $priceTotal += $ticket->getPrice();
        }
        return $priceTotal;
    }
    public function createCodeReserv(){
        $date = $this->getDateVisite();
        $id = $this->getId();
        return 'D' . date_format($date, 'dmY') . '-C' . rand();
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Commande
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }
}
