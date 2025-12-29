<?php

declare(strict_types=1);

namespace App\Enums;

enum AnnouncementStatus: string
{
    case ACTIVE = 'active';                 // Active et visible
    case PAUSED = 'paused';                 // En pause
    case CLOSED = 'closed';                 // Poste pourvu/Fermée
    case EXPIRED = 'expired';               // Expirée
    case ARCHIVED = 'archived';             // Archivée

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::PAUSED => 'En pause',
            self::CLOSED => 'Fermée',
            self::EXPIRED => 'Expirée',
            self::ARCHIVED => 'Archivée',
        };
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::PAUSED => 'yellow',
            self::CLOSED => 'blue',
            self::EXPIRED => 'red',
            self::ARCHIVED => 'gray',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function canBeEdited(): bool
    {
        return \in_array($this, [self::ACTIVE, self::PAUSED], true);
    }

    /**
     * Retourne le libellé court du statut
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::PAUSED => 'Pause',
            self::CLOSED => 'Fermée',
            self::EXPIRED => 'Expirée',
            self::ARCHIVED => 'Archivée',
        };
    }

    /**
     * Retourne la priorité d'affichage du statut
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::ACTIVE => 1,
            self::PAUSED => 2,
            self::CLOSED => 3,
            self::EXPIRED => 4,
            self::ARCHIVED => 5,
        };
    }

    /**
     * Vérifie si le statut est en pause
     */
    public function isPaused(): bool
    {
        return $this === self::PAUSED;
    }

    /**
     * Vérifie si le statut est fermé
     */
    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    /**
     * Vérifie si le statut est expiré
     */
    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    /**
     * Vérifie si le statut est archivé
     */
    public function isArchived(): bool
    {
        return $this === self::ARCHIVED;
    }

    /**
     * Vérifie si le statut est visible (active ou en pause)
     */
    public function isVisible(): bool
    {
        return \in_array($this, [self::ACTIVE, self::PAUSED], true);
    }

    /**
     * Vérifie si le statut est terminé (fermé, expiré ou archivé)
     */
    public function isFinished(): bool
    {
        return \in_array($this, [self::CLOSED, self::EXPIRED, self::ARCHIVED], true);
    }

    /**
     * Retourne un tableau de choix pour les formulaires (libellé => valeur)
     */
    public static function getChoices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }
        return $choices;
    }

    /**
     * Retourne un tableau de toutes les valeurs possibles
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Vérifie si une valeur est valide pour cet enum
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::getValues(), true);
    }

    /**
     * Retourne le statut par défaut
     */
    public static function getDefault(): self
    {
        return self::ACTIVE;
    }

    /**
     * Retourne les statuts disponibles
     */
    public static function getAvailableStatuses(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de statuts disponibles
     */
    public static function getCount(): int
    {
        return count(self::cases());
    }

    /**
     * Vérifie si le statut est le statut par défaut
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }

    /**
     * Retourne un tableau de choix triés par priorité
     */
    public static function getChoicesByPriority(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }
        uasort($choices, fn($a, $b) => self::from($a)->getPriority() <=> self::from($b)->getPriority());
        return $choices;
    }

    /**
     * Retourne les statuts actifs (visibles)
     */
    public static function getVisibleStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->isVisible()
        );
    }

    /**
     * Retourne les statuts terminés
     */
    public static function getFinishedStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->isFinished()
        );
    }

    /**
     * Retourne les statuts éditables
     */
    public static function getEditableStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->canBeEdited()
        );
    }
}
