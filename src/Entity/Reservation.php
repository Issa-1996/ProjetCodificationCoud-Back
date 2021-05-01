<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $annee;

    //TEMPORARY DELETE
    private $lit;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="reservation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiant;

    /**
     * @ORM\OneToOne(targetEntity=Affectation::class, mappedBy="reservation", cascade={"persist", "remove"})
     */
    private $affectation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

        return $this;
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

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(Affectation $affectation): self
    {
        // set the owning side of the relation if necessary
        if ($affectation->getReservation() !== $this) {
            $affectation->setReservation($this);
        }

        $this->affectation = $affectation;

        return $this;
    }
}
