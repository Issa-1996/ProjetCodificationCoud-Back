<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FaculteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"faculte"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={"post",
 *
 *         "get_faculte"={
 *                 "method" ="GET",
 *                 "path"="/faculte",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access",
 *                 "normalization_context"={"groups"={"faculte"},"enable_max_depth"=true},
 *              },
 *    },
 *      itemOperations={"put","delete","get"}
 *     )
 * @ORM\Entity(repositoryClass=FaculteRepository::class)
 */
class Faculte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"all_student"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"all_student","faculte"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Departement::class, mappedBy="faculte")
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
