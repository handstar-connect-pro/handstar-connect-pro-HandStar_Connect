<?php

declare(strict_types=1);

namespace App\Enums;

enum PlayerLaterality: string
{
    case RIGHT_HANDED = 'RIGHT_HANDED';
    case LEFT_HANDED = 'LEFT_HANDED';
    case AMBIDEXTROUS = 'AMBIDEXTROUS';

    /**
     * Retourne le libellé complet de la latéralité.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::RIGHT_HANDED => 'Droitier',
            self::LEFT_HANDED => 'Gaucher',
            self::AMBIDEXTROUS => 'Ambidextre',
        };
    }

    /**
     * Retourne le libellé court de la latéralité.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::RIGHT_HANDED => 'Droit',
            self::LEFT_HANDED => 'Gauche',
            self::AMBIDEXTROUS => 'Ambi',
        };
    }

    /**
     * Retourne la priorité d'affichage de la latéralité.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::RIGHT_HANDED => 1,
            self::LEFT_HANDED => 2,
            self::AMBIDEXTROUS => 3,
        };
    }

    /**
     * Vérifie si le joueur est droitier.
     */
    public function isRightHanded(): bool
    {
        return $this === self::RIGHT_HANDED;
    }

    /**
     * Vérifie si le joueur est gaucher.
     */
    public function isLeftHanded(): bool
    {
        return $this === self::LEFT_HANDED;
    }

    /**
     * Vérifie si le joueur est ambidextre.
     */
    public function isAmbidextrous(): bool
    {
        return $this === self::AMBIDEXTROUS;
    }

    /**
     * Retourne la main dominante du joueur.
     */
    public function getDominantHand(): string
    {
        return match ($this) {
            self::RIGHT_HANDED => 'Droite',
            self::LEFT_HANDED => 'Gauche',
            self::AMBIDEXTROUS => 'Les deux',
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
     * Retourne la latéralité par défaut.
     */
    public static function getDefault(): self
    {
        return self::RIGHT_HANDED;
    }

    /**
     * Retourne les latéralités disponibles.
     */
    public static function getAvailableLaterality(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de latéralités disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si la latéralité est la latéralité par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }

    /**
     * Retourne le pourcentage de joueurs avec cette latéralité (statistiques approximatives).
     */
    public function getPercentage(): float
    {
        return match ($this) {
            self::RIGHT_HANDED => 85.0,
            self::LEFT_HANDED => 10.0,
            self::AMBIDEXTROUS => 5.0,
        };
    }
}
