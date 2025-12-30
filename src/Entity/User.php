<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\UserProfil;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entité User représentant un utilisateur de la plateforme HandStar Connect.
 *
 * Cette entité implémente les interfaces de sécurité Symfony pour l'authentification
 * et gère les informations personnelles, le profil utilisateur et les permissions.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 50, enumType: UserProfil::class)]
    private ?UserProfil $profil = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Retourne l'identifiant unique de l'utilisateur.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le prénom de l'utilisateur.
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Définit le prénom de l'utilisateur.
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Retourne le nom de famille de l'utilisateur.
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Définit le nom de famille de l'utilisateur.
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Retourne le profil utilisateur (énumération UserProfil).
     *
     * Le profil détermine le type de compte et les fonctionnalités accessibles
     */
    public function getProfil(): ?UserProfil
    {
        return $this->profil;
    }

    /**
     * Définit le profil utilisateur.
     */
    public function setProfil(UserProfil|string|null $profil): static
    {
        if ($profil === null) {
            $this->profil = null;
        } elseif (\is_string($profil)) {
            $this->profil = UserProfil::from($profil);
        } else {
            $this->profil = $profil;
        }

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getProfilString(): string
    {
        return $this->profil?->value ?? '';
    }

    /**
     * Retourne l'adresse email de l'utilisateur.
     *
     * L'email sert d'identifiant unique pour la connexion
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'adresse email de l'utilisateur.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Retourne l'identifiant visuel de l'utilisateur (interface UserInterface).
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Retourne les rôles de l'utilisateur (interface UserInterface).
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantit que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles Liste des rôles à attribuer
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe hashé (interface PasswordAuthenticatedUserInterface).
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe hashé.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * S'assure que la session ne contient pas de hash de mot de passe réel
     * en les hashant avec CRC32C, supporté depuis Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = \hash('crc32c', $this->password);

        return $data;
    }

    /**
     * Vérifie si l'email de l'utilisateur a été vérifié.
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Définit l'état de vérification de l'email.
     */
    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Retourne la date de création du compte.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Définit la date de création du compte.
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Retourne la date de dernière mise à jour du compte.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Définit la date de dernière mise à jour du compte.
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Retourne le nom complet de l'utilisateur (prénom + nom).
     */
    public function getDisplayName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    /**
     * Représentation textuelle de l'utilisateur (utilisée par EasyAdmin et autres).
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }
}
