<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * * @ApiResource(
 *     routePrefix="/admin",
 *     collectionOperations={"post","GET"},
 *     itemOperations={"PUT", "GET"},
 *
 *     normalizationContext={"groups"={"reser:read"}},
 *     denormalizationContext={"groups"={"reser:write"}},
 * )
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reser:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank( message="l'année est obligatoire")
     * @Groups({"reser:read","reser:write"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Lit::class, inversedBy="reservations")
     * @Groups({"reser:read","reser:write"})
     */
    private $lit;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="reservation")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reser:read","reser:write"})
     */
    private $etudiant;

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
}
