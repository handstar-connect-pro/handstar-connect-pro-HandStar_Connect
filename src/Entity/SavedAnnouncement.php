<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SavedAnnouncementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SavedAnnouncementRepository::class)]
#[Broadcast]
class SavedAnnouncement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Notes = null;

    #[ORM\ManyToOne(targetEntity: Announcement::class, inversedBy: 'savedAnnouncements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Announcement $announcement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotes(): ?string
    {
        return $this->Notes;
    }

    public function setNotes(string $Notes): static
    {
        $this->Notes = $Notes;

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
}
