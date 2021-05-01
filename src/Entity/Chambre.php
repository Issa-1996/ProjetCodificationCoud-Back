<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * * @ApiResource(
 *     routePrefix="/admin",
 *     collectionOperations={"post","GET"},
 *     itemOperations={"PUT", "GET"}
 * )
 * @ORM\Entity(repositoryClass=ChambreRepository::class)
 */
class Chambre
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombrelit;

    /**
     * @ORM\ManyToOne(targetEntity=Pavillon::class, inversedBy="chambres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pavillon;

    /**
     * @ORM\OneToMany(targetEntity=Lit::class, mappedBy="chambre")
     */
    private $lits;

    public function __construct()
    {
        $this->lits = new ArrayCollection();
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

    public function getNombrelit(): ?string
    {
        return $this->nombrelit;
    }

    public function setNombrelit(?string $nombrelit): self
    {
        $this->nombrelit = $nombrelit;

        return $this;
    }

    public function getPavillon(): ?Pavillon
    {
        return $this->pavillon;
    }

    public function setPavillon(?Pavillon $pavillon): self
    {
        $this->pavillon = $pavillon;

        return $this;
    }

    /**
     * @return Collection|Lit[]
     */
    public function getLits(): Collection
    {
        return $this->lits;
    }

    public function addLit(Lit $lit): self
    {
        if (!$this->lits->contains($lit)) {
            $this->lits[] = $lit;
            $lit->setChambre($this);
        }

        return $this;
    }

    public function removeLit(Lit $lit): self
    {
        if ($this->lits->removeElement($lit)) {
            // set the owning side to null (unless already changed)
            if ($lit->getChambre() === $this) {
                $lit->setChambre(null);
            }
        }

        return $this;
    }
}
