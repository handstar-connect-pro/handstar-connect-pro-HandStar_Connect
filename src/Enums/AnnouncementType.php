<?php

declare(strict_types=1);

namespace App\Enums;

enum AnnouncementType: string
{
    case JOB_OFFER = 'job_offer';           // Offre d'emploi (Club cherche)
    case JOB_SEEKING = 'job_seeking';       // Recherche d'emploi (Profil cherche)

    public function getLabel(): string
    {
        return match ($this) {
            self::JOB_OFFER => 'Offre d\'emploi',
            self::JOB_SEEKING => 'Recherche d\'emploi',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::JOB_OFFER => 'Club/Structure cherche un profil',
            self::JOB_SEEKING => 'Professionnel cherche une opportunité',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::JOB_OFFER => 'briefcase',
            self::JOB_SEEKING => 'user-search',
        };
    }

    /**
     * Retourne un tableau de choix pour les formulaires (libellé => valeur).
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
     * Retourne un tableau de toutes les valeurs possibles.
     */
    public static function getValues(): array
    {
        return \array_column(self::cases(), 'value');
    }

    /**
     * Vérifie si une valeur est valide pour cet enum.
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::getValues(), true);
    }

    /**
     * Retourne le type d'annonce par défaut.
     */
    public static function getDefault(): self
    {
        return self::JOB_OFFER;
    }

    /**
     * Retourne le libellé court du type d'annonce.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::JOB_OFFER => 'Offre',
            self::JOB_SEEKING => 'Recherche',
        };
    }

    /**
     * Retourne la priorité d'affichage du type d'annonce.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::JOB_OFFER => 1,
            self::JOB_SEEKING => 2,
        };
    }

    /**
     * Vérifie si le type d'annonce est une offre d'emploi.
     */
    public function isJobOffer(): bool
    {
        return $this === self::JOB_OFFER;
    }

    /**
     * Vérifie si le type d'annonce est une recherche d'emploi.
     */
    public function isJobSeeking(): bool
    {
        return $this === self::JOB_SEEKING;
    }

    /**
     * Retourne un tableau de choix triés par priorité.
     */
    public static function getChoicesByPriority(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }
        \uasort($choices, fn ($a, $b) => self::from($a)->getPriority() <=> self::from($b)->getPriority());

        return $choices;
    }

    /**
     * Retourne les types d'annonce disponibles.
     */
    public static function getAvailableTypes(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de types d'annonce disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si le type d'annonce est le type par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }
}
