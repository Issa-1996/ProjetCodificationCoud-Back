<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EtudiantRepository;
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
 *     normalizationContext={"groups"={"etudiant:read"}},
 *     denormalizationContext={"groups"={"etudiant:write"}},
 * )
 * @ORM\Entity(repositoryClass=EtudiantRepository::class)
 */
class Etudiant extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="le cni est obligatoire")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $cni;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank( message="le date de naissance est obligatoire")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="le sexe est obligatoire")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="l'email est obligatoire")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Assert\NotBlank( message="l'avatar est obligatoire")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="etudiant")
     * @Groups({"etudiant:read","etudiant:write"})
     */
    private $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setEtudiant($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEtudiant() === $this) {
                $reservation->setEtudiant(null);
            }
        }

        return $this;
    }
}
