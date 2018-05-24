<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\CommandeRepository")
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
     * @var int
     *
     * @ORM\Column(name="PriceTotal", type="integer")
     * @Assert\Type("integer")
     */
    private $priceTotal = 12;

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
     * Set priceTotal
     *
     * @param integer $priceTotal
     *
     * @return Commande
     */
    public function setPriceTotal($priceTotal)
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }

    /**
     * Get priceTotal
     *
     * @return int
     */
    public function getPriceTotal()
    {
        return $this->priceTotal;
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
}
