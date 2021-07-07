<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "etudiant" = "Etudiant"})
 * @UniqueEntity("username")
 * @ApiFilter(SearchFilter::class, properties={"roles":"partial", "username":"exact"})
 * @ApiResource(
 *      attributes={"pagination_items_per_page"=5},
 *      collectionOperations={
 *          "post"={
 *              "path"="/admin/inscription",
 *          },
 *            "import_list"={
 *               "method"="POST",
 *               "path"="/admin/importList",
 *             },
 *          "get"={
 *              "security"="is_granted('ROLE_ETUDIANT')",
 *              "security_message"="Accéss limité.",
 *              "path"="/admin/liste",
 *              "normalization_context"={"groups"={"all_student"},"enable_max_depth"=true}               
 *          },
 *      },
 *      itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Accéss limité.",
 *              "path"="/admin/{id}",
 *              "normalization_context"={"groups"={"all_student"},"enable_max_depth"=true}
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Accéss limité.",
 *              "path"="/admin/archivage/{id}",
 *              "normalization_context"={"groups"={"archiver"},"enable_max_depth"=true}
 *          }
 *      }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"all_student"})
     * @Groups ({"archiver", "reservation_etu"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Groups ({"all_student", "reservation_read", "all_etudiant", "reservation_etu"})
     * @Groups ({"archiver"})
     */
    private $username;

     /**
     * @ORM\Column(type="json")
     * @Groups ({"all_student"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Groups ({"archiver"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"all_student", "reservation_read", "all_etudiant", "reservation_etu"})
     * @Groups ({"archiver"})
     */
    private $prenoms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"all_student", "reservation_read", "all_etudiant", "reservation_etu"})
     * @Groups ({"archiver"})
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @Groups ({"archiver"})
     * @Groups ({"all_student"})
     */
    private $archivage;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

     /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password,PASSWORD_ARGON2ID);

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenom): self
    {
        $this->prenoms = $prenom;

        return $this;
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

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(?bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
}
