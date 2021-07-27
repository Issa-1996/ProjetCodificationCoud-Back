<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChambreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"chambre"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={"post",
 *
 *         "get_faculte"={
 *                 "method" ="GET",
 *                 "path"="/chambre",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access",
 *                 "normalization_context"={"groups"={"chambre"},"enable_max_depth"=true},
 *              },
 *    },
 *      itemOperations={"put","delete","get"}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"id":"exact", "pavillon.nom":"exact"})
 * @ORM\Entity(repositoryClass=ChambreRepository::class)
 */
class Chambre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"chambre"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"chambre"})
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"chambre"})
     */
    private $nombrelit;

    /**
     * @ORM\ManyToOne(targetEntity=Pavillon::class, inversedBy="chambres", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"chambre"})
     */
    private $pavillon;

    /**
     * @ORM\OneToMany(targetEntity=Lit::class, mappedBy="chambre",cascade={"persist"})
     * @Groups ({"chambre"})
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
