<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/etudiant/inscription",
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_ETUDIANT')",
 *              "security_message"="Permission denied.",
 *              "path"="/etudiant/liste",
 *              "normalization_context"={"groups"={"all_students"},"enable_max_depth"=true},
 *          },
 *          "getusers"={
 *              "method"="get",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Permission non autorisée.",
 *              "path"="/etudiant/reservation",
 *              "normalization_context"={"groups"={"all_student"},"enable_max_depth"=true}
 *          }
 *      },
 *      itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ETUDIANT')",
 *              "security_message"="Permission non autorisée.",
 *              "path"="/etudiant/{id}",
 *              "normalization_context"={"groups"={"all_student"},"enable_max_depth"=true}
 *          }
 *      }
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
     */
    private $cni;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="etudiant")
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
