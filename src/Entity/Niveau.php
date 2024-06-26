<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"niveau"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={"post",
 *
 *         "get_niveau"={
 *                 "method" ="GET",
 *                 "path"="/niveau",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access"
 *              },
 *     "get_niveaux"={
 *                 "method" ="GET",
 *                 "path"="/niveau/liste",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access"
 *              },
 *    },
 *      itemOperations={"put","delete","get"}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"id":"exact", "departement.nom":"exact"})
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
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
     * @Groups ({"all_student","niveau"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="niveaux", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"all_student"})
     */
    private $departement;

    /**
     * @ORM\OneToMany(targetEntity=QuotaLit::class, mappedBy="niveau")
     */
    private $quotaLits;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="niveau")
     */
    private $etudiants;

    public function __construct()
    {
        $this->quotaLits = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
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

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setNiveau($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getNiveau() === $this) {
                $etudiant->setNiveau(null);
            }
        }

        return $this;
    }
}
