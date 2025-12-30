<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\ResponseStatus;
use App\Repository\AnnouncementResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AnnouncementResponseRepository::class)]
#[Broadcast]
class AnnouncementResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::STRING, enumType: ResponseStatus::class)]
    private ?ResponseStatus $status = null;

    #[ORM\Column]
    private ?bool $isRead = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachmentPath = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Announcement::class, inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Announcement $announcement = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = ResponseStatus::PENDING;
        $this->isRead = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?ResponseStatus
    {
        return $this->status;
    }

    public function setStatus(ResponseStatus $status): static
    {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function isRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getAttachmentPath(): ?string
    {
        return $this->attachmentPath;
    }

    public function setAttachmentPath(?string $attachmentPath): static
    {
        $this->attachmentPath = $attachmentPath;

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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): static
    {
        $this->announcement = $announcement;

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

    /**
     * Vérifie si la réponse est en attente
     */
    public function isPending(): bool
    {
        return $this->status === ResponseStatus::PENDING;
    }

    /**
     * Vérifie si la réponse a été acceptée
     */
    public function isAccepted(): bool
    {
        return $this->status === ResponseStatus::ACCEPTED;
    }

    /**
     * Vérifie si la réponse a été refusée
     */
    public function isRejected(): bool
    {
        return $this->status === ResponseStatus::REJECTED;
    }

    /**
     * Marque la réponse comme lue
     */
    public function markAsRead(): static
    {
        $this->isRead = true;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Marque la réponse comme non lue
     */
    public function markAsUnread(): static
    {
        $this->isRead = false;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Vérifie si la réponse a une pièce jointe
     */
    public function hasAttachment(): bool
    {
        return $this->attachmentPath !== null && $this->attachmentPath !== '';
    }

    /**
     * Représentation textuelle de la réponse
     */
    public function __toString(): string
    {
        return sprintf(
            'Réponse #%d à l\'annonce "%s"',
            $this->id,
            $this->announcement?->getOfferTitle() ?? 'Inconnue'
        );
    }
}
