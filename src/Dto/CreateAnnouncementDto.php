<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\AnnouncementType;
use App\Enums\UserProfil;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour la création d'une annonce.
 */
class CreateAnnouncementDto
{
    #[Assert\NotBlank(message: 'Le type d\'offre est requis')]
    #[Assert\Choice(callback: [AnnouncementType::class, 'cases'], message: 'Type d\'offre invalide')]
    public ?AnnouncementType $offerType = null;

    #[Assert\NotBlank(message: 'Le titre de l\'offre est requis')]
    #[Assert\Length(
        min: 5,
        max: 200,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $offerTitle = null;

    #[Assert\NotBlank(message: 'La description de l\'offre est requise')]
    #[Assert\Length(
        min: 20,
        max: 2000,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $offerDescription = null;

    #[Assert\NotBlank(message: 'Le profil utilisateur cible est requis')]
    #[Assert\Choice(callback: [UserProfil::class, 'cases'], message: 'Profil utilisateur invalide')]
    public ?UserProfil $offerUserProfil = null;

    #[Assert\NotBlank(message: 'Le poste recherché est requis')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le poste doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le poste ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $positionSought = null;

    #[Assert\NotBlank(message: 'La division concernée est requise')]
    #[Assert\Choice(callback: [LevelDivision::class, 'cases'], message: 'Division invalide')]
    public ?LevelDivision $leagueConcerned = null;

    #[Assert\NotBlank(message: 'La région est requise')]
    #[Assert\Choice(callback: [ListRegion::class, 'cases'], message: 'Région invalide')]
    public ?ListRegion $location = null;

    #[Assert\Type('\DateTimeInterface', message: 'La date d\'expiration doit être une date valide')]
    #[Assert\GreaterThan('today', message: 'La date d\'expiration doit être dans le futur')]
    public ?\DateTimeInterface $expiresAt = null;

    /**
     * Convertit le DTO en tableau pour faciliter l'utilisation.
     */
    public function toArray(): array
    {
        return [
            'offerType' => $this->offerType,
            'offerTitle' => $this->offerTitle,
            'offerDescription' => $this->offerDescription,
            'offerUserProfil' => $this->offerUserProfil,
            'positionSought' => $this->positionSought,
            'leagueConcerned' => $this->leagueConcerned,
            'location' => $this->location,
            'expiresAt' => $this->expiresAt,
        ];
    }

    /**
     * Crée un DTO à partir d'un tableau.
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        if (isset($data['offerType'])) {
            $dto->offerType = $data['offerType'] instanceof AnnouncementType
                ? $data['offerType']
                : AnnouncementType::tryFrom($data['offerType']);
        }

        if (isset($data['offerTitle'])) {
            $dto->offerTitle = (string) $data['offerTitle'];
        }

        if (isset($data['offerDescription'])) {
            $dto->offerDescription = (string) $data['offerDescription'];
        }

        if (isset($data['offerUserProfil'])) {
            $dto->offerUserProfil = $data['offerUserProfil'] instanceof UserProfil
                ? $data['offerUserProfil']
                : UserProfil::tryFrom($data['offerUserProfil']);
        }

        if (isset($data['positionSought'])) {
            $dto->positionSought = (string) $data['positionSought'];
        }

        if (isset($data['leagueConcerned'])) {
            $dto->leagueConcerned = $data['leagueConcerned'] instanceof LevelDivision
                ? $data['leagueConcerned']
                : LevelDivision::tryFrom($data['leagueConcerned']);
        }

        if (isset($data['location'])) {
            $dto->location = $data['location'] instanceof ListRegion
                ? $data['location']
                : ListRegion::tryFrom($data['location']);
        }

        if (isset($data['expiresAt'])) {
            $dto->expiresAt = $data['expiresAt'] instanceof \DateTimeInterface
                ? $data['expiresAt']
                : new \DateTimeImmutable($data['expiresAt']);
        }

        return $dto;
    }
}
