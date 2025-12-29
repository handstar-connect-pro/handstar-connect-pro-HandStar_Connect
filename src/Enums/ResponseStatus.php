<?php

declare(strict_types=1);

namespace App\Enums;

enum ResponseStatus: string
{
    case PENDING = 'pending';               // En attente
    case VIEWED = 'viewed';                 // Vue
    case SHORTLISTED = 'shortlisted';       // Présélectionné
    case INTERVIEW = 'interview';           // Entretien prévu
    case ACCEPTED = 'accepted';             // Accepté
    case REJECTED = 'rejected';             // Refusé
    case WITHDRAWN = 'withdrawn';           // Retirée par le candidat

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::VIEWED => 'Vue',
            self::SHORTLISTED => 'Présélectionné',
            self::INTERVIEW => 'Entretien',
            self::ACCEPTED => 'Accepté',
            self::REJECTED => 'Refusé',
            self::WITHDRAWN => 'Retirée',
        };
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::VIEWED => 'blue',
            self::SHORTLISTED => 'yellow',
            self::INTERVIEW => 'purple',
            self::ACCEPTED => 'green',
            self::REJECTED => 'red',
            self::WITHDRAWN => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::VIEWED => 'eye',
            self::SHORTLISTED => 'star',
            self::INTERVIEW => 'calendar',
            self::ACCEPTED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::WITHDRAWN => 'arrow-left',
        };
    }

    /**
     * Retourne le libellé court du statut
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Attente',
            self::VIEWED => 'Vue',
            self::SHORTLISTED => 'Présélection',
            self::INTERVIEW => 'Entretien',
            self::ACCEPTED => 'Accepté',
            self::REJECTED => 'Refusé',
            self::WITHDRAWN => 'Retiré',
        };
    }

    /**
     * Retourne la priorité d'affichage du statut
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::ACCEPTED => 1,
            self::INTERVIEW => 2,
            self::SHORTLISTED => 3,
            self::VIEWED => 4,
            self::PENDING => 5,
            self::REJECTED => 6,
            self::WITHDRAWN => 7,
        };
    }

    /**
     * Vérifie si le statut est en attente
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    /**
     * Vérifie si le statut est vu
     */
    public function isViewed(): bool
    {
        return $this === self::VIEWED;
    }

    /**
     * Vérifie si le statut est présélectionné
     */
    public function isShortlisted(): bool
    {
        return $this === self::SHORTLISTED;
    }

    /**
     * Vérifie si le statut est en entretien
     */
    public function isInterview(): bool
    {
        return $this === self::INTERVIEW;
    }

    /**
     * Vérifie si le statut est accepté
     */
    public function isAccepted(): bool
    {
        return $this === self::ACCEPTED;
    }

    /**
     * Vérifie si le statut est refusé
     */
    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    /**
     * Vérifie si le statut est retiré
     */
    public function isWithdrawn(): bool
    {
        return $this === self::WITHDRAWN;
    }

    /**
     * Vérifie si le statut est positif (accepté)
     */
    public function isPositive(): bool
    {
        return $this === self::ACCEPTED;
    }

    /**
     * Vérifie si le statut est négatif (refusé ou retiré)
     */
    public function isNegative(): bool
    {
        return \in_array($this, [self::REJECTED, self::WITHDRAWN], true);
    }

    /**
     * Vérifie si le statut est en cours (pas encore terminé)
     */
    public function isInProgress(): bool
    {
        return \in_array($this, [self::PENDING, self::VIEWED, self::SHORTLISTED, self::INTERVIEW], true);
    }

    /**
     * Vérifie si le statut est terminé (accepté, refusé ou retiré)
     */
    public function isFinished(): bool
    {
        return \in_array($this, [self::ACCEPTED, self::REJECTED, self::WITHDRAWN], true);
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
        return self::PENDING;
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
     * Retourne les statuts en cours
     */
    public static function getInProgressStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->isInProgress()
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
     * Retourne les statuts positifs
     */
    public static function getPositiveStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->isPositive()
        );
    }

    /**
     * Retourne les statuts négatifs
     */
    public static function getNegativeStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->isNegative()
        );
    }
}
