<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     routePrefix="/admin",
 *     collectionOperations={"post","GET"},
 *     itemOperations={"PUT", "GET"}
 * )
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Pavillon::class, mappedBy="campus")
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
