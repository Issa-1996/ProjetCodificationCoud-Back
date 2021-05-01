<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuotaLitRepository;
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
 *     normalizationContext={"groups"={"quota:read"}},
 *     denormalizationContext={"groups"={"quota:write"}},
 * )
 * @ORM\Entity(repositoryClass=QuotaLitRepository::class)
 */
class QuotaLit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"quota:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank( message="le quota est obligatoire")
     * @Groups({"quota:read","quota:write"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="quotaLits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"quota:read","quota:write"})
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=Lit::class, mappedBy="quota")
     * @Groups({"quota:read","quota:write"})
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
