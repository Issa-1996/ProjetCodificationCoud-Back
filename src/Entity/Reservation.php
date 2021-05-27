<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReservationRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * * @ApiResource(
 *      attributes={
 *          "denormalization_context"={"groups"={"reservation_create"},"enable_max_depth"=true},
 *      },
 *      collectionOperations={
 *          "post"={
 *               "security"="is_granted('ROLE_ETUDIANT')",
 *               "security_message"="Permission denied.",
 *               "path"="/etudiant/reserver",
 *           },
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Permission denied.",
 *              "path"="api/reservation/etudiant",
 *              "normalization_context"={"groups"={"reservation_read"},"enable_max_depth"=true},
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "defaults"={"id"=null}
 *          }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "etudiant.id":"exact"})
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"all_student"})
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank( message="l'année est obligatoire" )
     * @Groups ({"all_student"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="reservation")
     * @Assert\NotBlank( message="l'étudiant est obligatoire" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reservation_create", "reservation_read"})
     */
    private $etudiant;

    /**
     * @Groups ({"all_student"})
     * @ORM\OneToOne(targetEntity=Affectation::class, mappedBy="reservation", cascade={"persist", "remove"})
     */
    private $affectation;

    public function __construct(){
        $this->annee = new DateTime;
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

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(Affectation $affectation): self
    {
        // set the owning side of the relation if necessary
        if ($affectation->getReservation() !== $this) {
            $affectation->setReservation($this);
        }

        $this->affectation = $affectation;

        return $this;
    }
}
