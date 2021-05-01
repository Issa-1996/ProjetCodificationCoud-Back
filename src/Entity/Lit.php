<?php

namespace App\Entity;

use App\Repository\LitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LitRepository::class)
 */
class Lit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="lits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity=QuotaLit::class, inversedBy="lits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quota;

    //TEMPORARY DELETE
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Affectation::class, mappedBy="lit")
     */
    private $affectations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getQuota(): ?QuotaLit
    {
        return $this->quota;
    }

    public function setQuota(?QuotaLit $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLit($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLit() === $this) {
                $reservation->setLit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setLit($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getLit() === $this) {
                $affectation->setLit(null);
            }
        }

        return $this;
    }
}
