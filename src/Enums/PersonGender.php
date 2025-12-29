<?php

declare(strict_types=1);

namespace App\Enums;

enum PersonGender: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';

    /**
     * Retourne le libellé complet du genre.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Homme',
            self::FEMALE => 'Femme',
        };
    }

    /**
     * Retourne le libellé court du genre.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::MALE => 'H',
            self::FEMALE => 'F',
        };
    }

    /**
     * Retourne la priorité d'affichage du genre.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::MALE => 1,
            self::FEMALE => 2,
        };
    }

    /**
     * Vérifie si le genre est masculin.
     */
    public function isMale(): bool
    {
        return $this === self::MALE;
    }

    /**
     * Vérifie si le genre est féminin.
     */
    public function isFemale(): bool
    {
        return $this === self::FEMALE;
    }

    /**
     * Vérifie si le genre est spécifié
     * Note: Tous les genres sont maintenant spécifiés.
     */
    public function isSpecified(): bool
    {
        return true;
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
     * Retourne le genre opposé.
     */
    public function getOpposite(): self
    {
        return match ($this) {
            self::MALE => self::FEMALE,
            self::FEMALE => self::MALE,
        };
    }

    /**
     * Retourne le genre par défaut.
     */
    public static function getDefault(): self
    {
        return self::MALE;
    }

    /**
     * Retourne les genres disponibles.
     */
    public static function getAvailableGenders(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de genres disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si le genre est le genre par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }
}
