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
 *          "pagination_items_per_page"=5,
 *          "pagination_client_enabled"=true,
 *          "pagination_client_items_per_page"=true
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
 *              "path"="/reservation/etudiant",
 *              "normalization_context"={"groups"={"reservation_read"},"enable_max_depth"=true},
 *          },
 *          "getreservations"={
 *              "method" ="GET",
 *              "security"="is_granted('ROLE_ETUDIANT')",
 *              "security_message"="Permission denied.",
 *              "path"="/reservations",
 *              "normalization_context"={"groups"={"reservation_etu"},"enable_max_depth"=true},
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "defaults"={"id"=null}
 *          }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "etudiant.niveau.nom":"exact", "affectation.annee":"exact", "annee":"exact", "etudiant.username":"exact"})
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"all_student", "reservation_etu"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message="l'année est obligatoire" )
     * @Groups ({"all_student", "reservation_etu"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="reservation")
     * @Assert\NotBlank( message="l'étudiant est obligatoire" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reservation_create", "reservation_read", "reservation_etu"})
     */
    private $etudiant;

    /**
     * @Groups ({"all_student", "reservation_etu"})
     * @ORM\OneToOne(targetEntity=Affectation::class, mappedBy="reservation", cascade={"persist", "remove"})
     */
    private $affectation;

    public function __construct(){
        $annee = new DateTime;
        $this->annee = $annee->format('Y');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
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
