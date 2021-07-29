<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuotaLitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     attributes={
 *       "normalization_context"={"groups"={"quota_read"},"enable_max_depth"=true},
 *     },
 *      collectionOperations={
 *         "get_faculte"={
 *                 "method" ="GET",
 *                 "path"="/quotas",
 *                 "security"="is_granted('ROLE_ETUDIANT')",
 *                 "security_message"="Vous n'avez pas d'access",
 *                 "normalization_context"={"groups"={"quota_read"},"enable_max_depth"=true},
 *              },
 *    },
 *
 * )
 * @ORM\Entity(repositoryClass=QuotaLitRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"niveau.nom":"exact", "annee":"exact"})
 */
class QuotaLit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"quota_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="quotaLits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=Lit::class, mappedBy="quota",cascade={"persist"})
     */
    private $lits;

    public function __construct()
    {
        $this->lits = new ArrayCollection();
        $annee = new DateTime;
        $this->annee = $annee->format('Y');
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
