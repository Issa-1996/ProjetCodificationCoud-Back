<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/etudiant/inscription",
 *          },
 *          "getUser"={
 *              "method"="get",
 *              "security"="is_granted('ROLE_ETUDIANT')",
 *              "security_message"="Permission denied.",
 *              "path"="/etudiant/liste",
 *              "normalization_context"={"groups"={"all_student"},"enable_max_depth"=true},
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
     * @Assert\NotBlank( message="le numéro d'entité est obligatoire" )
     *
     */
    private $numIdentite;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message="le date de naisssance est obligatoire" )
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank( message="le sexe est obligatoire" )
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="l'email est obligatoire" )
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="etudiant")
     * @Assert\NotBlank( message="la reservation est obligatoire" )
     */
    private $reservation;

    /**
     * @ORM\Column(type="string")
     * @Groups ({"all_student"})
     * @Assert\NotBlank( message="le moyenne est obligatoire" )
     */
    private $moyenne;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="etudiants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"all_student"})
     */
    private $niveau;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

  /*  public function getId(): ?int
    {
        return $this->id;
    }*/

    public function getNumIdentite(): ?string
    {
        return $this->numIdentite;
    }

    public function setNumIdentite(string $cni): self
    {
        $this->numIdentite = $cni;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(string $datenaissance): self
    {
        $this->dateNaissance = $datenaissance;

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

    public function getMoyenne(): ?string
    {
        return $this->moyenne;
    }

    public function setMoyenneSession(string $moyenne): self
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }
}
