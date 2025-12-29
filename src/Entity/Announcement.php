<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\AnnouncementStatus;
use App\Enums\AnnouncementType;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use App\Enums\UserProfil;
use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
#[Broadcast]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, enumType: AnnouncementType::class)]
    private ?AnnouncementType $offerType = null;

    #[ORM\Column(length: 255)]
    private ?string $offerTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $offerDescription = null;

    #[ORM\Column(type: Types::STRING, enumType: UserProfil::class)]
    private ?UserProfil $offerUserProfil = null;

    #[ORM\Column(length: 255)]
    private ?string $positionSought = null;

    #[ORM\Column(type: Types::STRING, enumType: LevelDivision::class)]
    private ?LevelDivision $leagueConcerned = null;

    #[ORM\Column(type: Types::STRING, enumType: ListRegion::class)]
    private ?ListRegion $location = null;

    #[ORM\Column(type: Types::STRING, enumType: AnnouncementStatus::class)]
    private ?AnnouncementStatus $offerStatus = null;

    #[ORM\Column]
    private ?int $viewCount = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::STRING, enumType: UserProfil::class)]
    private ?UserProfil $profil = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\OneToMany(targetEntity: AnnouncementResponse::class, mappedBy: 'announcement')]
    private Collection $responses;

    #[ORM\OneToMany(targetEntity: SavedAnnouncement::class, mappedBy: 'announcement')]
    private Collection $savedAnnouncements;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->savedAnnouncements = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfferType(): ?AnnouncementType
    {
        return $this->offerType;
    }

    public function setOfferType(AnnouncementType|string|null $offerType): static
    {
        if ($offerType === null) {
            $this->offerType = null;
        } elseif (is_string($offerType)) {
            $this->offerType = AnnouncementType::from($offerType);
        } else {
            $this->offerType = $offerType;
        }

        return $this;
    }

    /**
     * Alternative setter for PropertyAccessor and forms.
     */
    public function setOfferTypeString(string $offerType): static
    {
        $this->offerType = AnnouncementType::from($offerType);

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getOfferTypeString(): string
    {
        return $this->offerType?->value ?? '';
    }

    public function getOfferTitle(): ?string
    {
        return $this->offerTitle;
    }

    public function setOfferTitle(string $offerTitle): static
    {
        $this->offerTitle = $offerTitle;

        return $this;
    }

    public function getOfferDescription(): ?string
    {
        return $this->offerDescription;
    }

    public function setOfferDescription(string $offerDescription): static
    {
        $this->offerDescription = $offerDescription;

        return $this;
    }

    public function getOfferUserProfil(): ?UserProfil
    {
        return $this->offerUserProfil;
    }

    public function setOfferUserProfil(UserProfil|string|null $offerUserProfil): static
    {
        if ($offerUserProfil === null) {
            $this->offerUserProfil = null;
        } elseif (is_string($offerUserProfil)) {
            $this->offerUserProfil = UserProfil::from($offerUserProfil);
        } else {
            $this->offerUserProfil = $offerUserProfil;
        }

        return $this;
    }

    /**
     * Alternative setter for PropertyAccessor and forms.
     */
    public function setOfferUserProfilString(string $offerUserProfil): static
    {
        $this->offerUserProfil = UserProfil::from($offerUserProfil);

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getOfferUserProfilString(): string
    {
        return $this->offerUserProfil?->value ?? '';
    }

    public function getPositionSought(): ?string
    {
        return $this->positionSought;
    }

    public function setPositionSought(string $positionSought): static
    {
        $this->positionSought = $positionSought;

        return $this;
    }

    public function getLeagueConcerned(): ?LevelDivision
    {
        return $this->leagueConcerned;
    }

    public function setLeagueConcerned(LevelDivision|string|null $leagueConcerned): static
    {
        if ($leagueConcerned === null) {
            $this->leagueConcerned = null;
        } elseif (is_string($leagueConcerned)) {
            $this->leagueConcerned = LevelDivision::from($leagueConcerned);
        } else {
            $this->leagueConcerned = $leagueConcerned;
        }

        return $this;
    }

    /**
     * Alternative setter for PropertyAccessor and forms.
     */
    public function setLeagueConcernedString(string $leagueConcerned): static
    {
        $this->leagueConcerned = LevelDivision::from($leagueConcerned);

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getLeagueConcernedString(): string
    {
        return $this->leagueConcerned?->value ?? '';
    }

    public function getLocation(): ?ListRegion
    {
        return $this->location;
    }

    public function setLocation(ListRegion|string|null $location): static
    {
        if ($location === null) {
            $this->location = null;
        } elseif (is_string($location)) {
            $this->location = ListRegion::from($location);
        } else {
            $this->location = $location;
        }

        return $this;
    }

    /**
     * Alternative setter for PropertyAccessor and forms.
     */
    public function setLocationString(string $location): static
    {
        $this->location = ListRegion::from($location);

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getLocationString(): string
    {
        return $this->location?->value ?? '';
    }

    public function getOfferStatus(): ?AnnouncementStatus
    {
        return $this->offerStatus;
    }

    public function setOfferStatus(AnnouncementStatus|string|null $offerStatus): static
    {
        if ($offerStatus === null) {
            $this->offerStatus = null;
        } elseif (is_string($offerStatus)) {
            $this->offerStatus = AnnouncementStatus::from($offerStatus);
        } else {
            $this->offerStatus = $offerStatus;
        }

        return $this;
    }

    /**
     * Alternative setter for PropertyAccessor and forms.
     */
    public function setOfferStatusString(string $offerStatus): static
    {
        $this->offerStatus = AnnouncementStatus::from($offerStatus);

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms.
     */
    public function getOfferStatusString(): string
    {
        return $this->offerStatus?->value ?? '';
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function incrementViewCount(): static
    {
        ++$this->viewCount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getProfil(): ?UserProfil
    {
        return $this->profil;
    }

    public function setProfil(UserProfil|string|null $profil): static
    {
        if ($profil === null) {
            $this->profil = null;
        } elseif (is_string($profil)) {
            $this->profil = UserProfil::from($profil);
        } else {
            $this->profil = $profil;
        }

        return $this;
    }

    /**
     * Alternative getter for PropertyAccessor and forms
     */
    public function getProfilString(): string
    {
        return $this->profil?->value ?? '';
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, AnnouncementResponse>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(AnnouncementResponse $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setAnnouncement($this);
        }

        return $this;
    }

    public function removeResponse(AnnouncementResponse $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getAnnouncement() === $this) {
                $response->setAnnouncement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SavedAnnouncement>
     */
    public function getSavedAnnouncements(): Collection
    {
        return $this->savedAnnouncements;
    }

    public function addSavedAnnouncement(SavedAnnouncement $savedAnnouncement): static
    {
        if (!$this->savedAnnouncements->contains($savedAnnouncement)) {
            $this->savedAnnouncements->add($savedAnnouncement);
            $savedAnnouncement->setAnnouncement($this);
        }

        return $this;
    }

    public function removeSavedAnnouncement(SavedAnnouncement $savedAnnouncement): static
    {
        if ($this->savedAnnouncements->removeElement($savedAnnouncement)) {
            // set the owning side to null (unless already changed)
            if ($savedAnnouncement->getAnnouncement() === $this) {
                $savedAnnouncement->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->offerTitle ?? 'Announcement #' . $this->id;
    }
}
