<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * * @ApiResource(
 *     routePrefix="/admin",
 *     collectionOperations={"post","GET"},
 *     itemOperations={"PUT", "GET"},
 *
 *     normalizationContext={"groups"={"lit:read"}},
 *     denormalizationContext={"groups"={"lit:write"}},
 * )
 * @ORM\Entity(repositoryClass=LitRepository::class)
 */
class Lit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"lit:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="le numero est obligatoire")
     * @Groups({"lit:read","lit:write"})
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="lits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"lit:read","lit:write"})
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity=QuotaLit::class, inversedBy="lits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"lit:read","lit:write"})
     */
    private $quota;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="lit")
     * @Groups({"lit:read","lit:write"})
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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
}
