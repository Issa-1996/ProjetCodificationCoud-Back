<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FaculteRepository;
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
 *     normalizationContext={"groups"={"faculte:read"}},
 *     denormalizationContext={"groups"={"faculte:write"}},
 * )
 * @ORM\Entity(repositoryClass=FaculteRepository::class)
 */
class Faculte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"faculte:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="le nom est obligatoire")
     * @Groups({"faculte:read","faculte:write"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Departement::class, mappedBy="faculte")
     * @Groups({"faculte:read","faculte:write"})
     */
    private $departements;

    public function __construct()
    {
        $this->departements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Departement[]
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements[] = $departement;
            $departement->setFaculte($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getFaculte() === $this) {
                $departement->setFaculte(null);
            }
        }

        return $this;
    }
}
