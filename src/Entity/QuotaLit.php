<?php

namespace App\Entity;

use App\Repository\QuotaLitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuotaLitRepository::class)
 */
class QuotaLit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="quotaLits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=Lit::class, mappedBy="quota")
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

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

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
            $lit->setQuota($this);
        }

        return $this;
    }

    public function removeLit(Lit $lit): self
    {
        if ($this->lits->removeElement($lit)) {
            // set the owning side to null (unless already changed)
            if ($lit->getQuota() === $this) {
                $lit->setQuota(null);
            }
        }

        return $this;
    }
}
