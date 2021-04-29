<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
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
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="niveaux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\OneToMany(targetEntity=QuotaLit::class, mappedBy="niveau")
     */
    private $quotaLits;

    public function __construct()
    {
        $this->quotaLits = new ArrayCollection();
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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection|QuotaLit[]
     */
    public function getQuotaLits(): Collection
    {
        return $this->quotaLits;
    }

    public function addQuotaLit(QuotaLit $quotaLit): self
    {
        if (!$this->quotaLits->contains($quotaLit)) {
            $this->quotaLits[] = $quotaLit;
            $quotaLit->setNiveau($this);
        }

        return $this;
    }

    public function removeQuotaLit(QuotaLit $quotaLit): self
    {
        if ($this->quotaLits->removeElement($quotaLit)) {
            // set the owning side to null (unless already changed)
            if ($quotaLit->getNiveau() === $this) {
                $quotaLit->setNiveau(null);
            }
        }

        return $this;
    }
}
