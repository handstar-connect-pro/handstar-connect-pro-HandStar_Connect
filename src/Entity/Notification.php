<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null; // 'info', 'success', 'warning', 'error', 'announcement', 'response'

    #[ORM\Column]
    private ?bool $isRead = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $actionUrl = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $actionLabel = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $metadata = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isRead = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;
        if ($isRead && $this->readAt === null) {
            $this->readAt = new \DateTimeImmutable();
        } elseif (!$isRead) {
            $this->readAt = null;
        }

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

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): static
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function getActionUrl(): ?string
    {
        return $this->actionUrl;
    }

    public function setActionUrl(?string $actionUrl): static
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    public function getActionLabel(): ?string
    {
        return $this->actionLabel;
    }

    public function setActionLabel(?string $actionLabel): static
    {
        $this->actionLabel = $actionLabel;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Marque la notification comme lue.
     */
    public function markAsRead(): static
    {
        $this->isRead = true;
        $this->readAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Marque la notification comme non lue.
     */
    public function markAsUnread(): static
    {
        $this->isRead = false;
        $this->readAt = null;

        return $this;
    }

    /**
     * Vérifie si la notification a une action.
     */
    public function hasAction(): bool
    {
        return $this->actionUrl !== null && $this->actionLabel !== null;
    }

    /**
     * Représentation textuelle de la notification.
     */
    public function __toString(): string
    {
        return $this->title ?? 'Notification #' . $this->id;
    }
}
