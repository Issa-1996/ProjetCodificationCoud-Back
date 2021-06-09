<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PavillonRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"pavillon"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={"post",
 *
 *         "get_faculte"={
 *                 "method" ="GET",
 *                 "path"="/pavillon",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas d'access",
 *                 "normalization_context"={"groups"={"pavillon"},"enable_max_depth"=true},
 *              },
 *    },
 *      itemOperations={"put","delete","get"}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"id":"exact", "campus.nom":"exact"})
 * @ORM\Entity(repositoryClass=PavillonRepository::class)
 */
class Pavillon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"pavillon"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pavillon"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="pavillons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"pavillon"})
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Chambre::class, mappedBy="pavillon")
     * @Groups ({"pavillon"})
     */
    private $chambres;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
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

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Chambre[]
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres[] = $chambre;
            $chambre->setPavillon($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getPavillon() === $this) {
                $chambre->setPavillon(null);
            }
        }

        return $this;
    }
}
