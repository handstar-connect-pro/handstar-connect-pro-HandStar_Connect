<?php

namespace App\Entity;

use App\Enums\ContactFonction;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use App\Enums\UserProfil;
use App\Repository\ClubRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
#[Broadcast]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $clubNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $contactFirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $contactLastName = null;

    #[ORM\Column(length: 50, enumType: ContactFonction::class)]
    private ?ContactFonction $contactPosition = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 50, enumType: ListRegion::class)]
    private ?ListRegion $region = null;

    #[ORM\Column(length: 50, enumType: LevelDivision::class)]
    private ?LevelDivision $division = null;

    #[ORM\Column(length: 50, enumType: UserProfil::class)]
    private ?UserProfil $profil = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubNumber(): ?string
    {
        return $this->clubNumber;
    }

    public function setClubNumber(string $clubNumber): static
    {
        $this->clubNumber = $clubNumber;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getContactFirstName(): ?string
    {
        return $this->contactFirstName;
    }

    public function setContactFirstName(string $contactFirstName): static
    {
        $this->contactFirstName = $contactFirstName;

        return $this;
    }

    public function getContactLastName(): ?string
    {
        return $this->contactLastName;
    }

    public function setContactLastName(string $contactLastName): static
    {
        $this->contactLastName = $contactLastName;

        return $this;
    }

    public function getContactPosition(): ?ContactFonction
    {
        return $this->contactPosition;
    }

    public function setContactPosition(ContactFonction $contactPosition): static
    {
        $this->contactPosition = $contactPosition;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?ListRegion
    {
        return $this->region;
    }

    public function setRegion(ListRegion $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getDivision(): ?LevelDivision
    {
        return $this->division;
    }

    public function setDivision(LevelDivision $division): static
    {
        $this->division = $division;

        return $this;
    }

    public function getProfil(): ?UserProfil
    {
        return $this->profil;
    }

    public function setProfil(UserProfil $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
