<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CampusRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"campus"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={"post",
 *
 *         "get_faculte"={
 *                 "method" ="GET",
 *                 "path"="/campus",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access",
 *                 "normalization_context"={"groups"={"campus"},"enable_max_depth"=true},
 *              },
 *    },
 *      itemOperations={"put","delete","get"}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"id":"exact", "nom":"exact"})
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"campus"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"campus"})
     * @Groups ({"all_student"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Pavillon::class, mappedBy="campus")
     * @Groups ({"campus"})
     */
    private $pavillons;

    public function __construct()
    {
        $this->pavillons = new ArrayCollection();
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
     * @return Collection|Pavillon[]
     */
    public function getPavillons(): Collection
    {
        return $this->pavillons;
    }

    public function addPavillon(Pavillon $pavillon): self
    {
        if (!$this->pavillons->contains($pavillon)) {
            $this->pavillons[] = $pavillon;
            $pavillon->setCampus($this);
        }

        return $this;
    }

    public function removePavillon(Pavillon $pavillon): self
    {
        if ($this->pavillons->removeElement($pavillon)) {
            // set the owning side to null (unless already changed)
            if ($pavillon->getCampus() === $this) {
                $pavillon->setCampus(null);
            }
        }

        return $this;
    }
}
