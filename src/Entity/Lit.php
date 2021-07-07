<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LitRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     attributes={
 *       "normalization_context"={"groups"={"lit_read"},"enable_max_depth"=true},
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=LitRepository::class)
 */
class Lit
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
     * @Groups ({"all_student", "lit_read", "reservation_etu"})
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="lits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *  @Groups ({"lit_read"})
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity=QuotaLit::class, inversedBy="lits")
     * @ORM\JoinColumn(nullable=true)
     *  @Groups ({ "lit_read"})
     */
    private $quota;

    /**
     * @ORM\OneToMany(targetEntity=Affectation::class, mappedBy="lit")
     */
    private $affectations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->affectations = new ArrayCollection();
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

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getQuota(): ?QuotaLit
    {
        return $this->quota;
    }

    public function setQuota(?QuotaLit $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setLit($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getLit() === $this) {
                $affectation->setLit(null);
            }
        }

        return $this;
    }
}
