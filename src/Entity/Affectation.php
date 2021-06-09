<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AffectationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AffectationRepository::class)
 */
class Affectation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lit::class, inversedBy="affectations", cascade={"persist"})
     * @Groups ({"all_student"})
     */
    private $lit;

    /**
     * @ORM\Column(type="string")
     */
    private $annee;

    /**
     * @ORM\OneToOne(targetEntity=Reservation::class, inversedBy="affectation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

    public function __construct(){
        $annee = new DateTime;
        $this->annee = $annee->format('Y');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLit(): ?Lit
    {
        return $this->lit;
    }

    public function setLit(?Lit $lit): self
    {
        $this->lit = $lit;

        return $this;
    }

    public function getAnnee(): string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }
}
