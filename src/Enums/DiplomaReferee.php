<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaReferee: string
{
    case ARBITRE_ELITE = 'ARBITRE_ELITE';
    case ARBITRE_NATIONALE = 'ARBITRE_NATIONALE';
    case ARBITRE_REGIONAL = 'ARBITRE_REGIONAL';
    case ARBITRE_DEPARTEMENTAL = 'ARBITRE_DEPARTEMENTAL';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::ARBITRE_ELITE => 'Arbitre - Elite',
            self::ARBITRE_NATIONALE => 'Arbitre - Nationale',
            self::ARBITRE_REGIONAL => 'Arbitre - Régional',
            self::ARBITRE_DEPARTEMENTAL => 'Arbitre - Départemental',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::ARBITRE_ELITE => 'Elite',
            self::ARBITRE_NATIONALE => 'Nationale',
            self::ARBITRE_REGIONAL => 'Régional',
            self::ARBITRE_DEPARTEMENTAL => 'Départemental',
        };
    }

    /**
     * Retourne le niveau du diplôme (1-4, 1 étant le plus élevé).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::ARBITRE_ELITE => 1,
            self::ARBITRE_NATIONALE => 2,
            self::ARBITRE_REGIONAL => 3,
            self::ARBITRE_DEPARTEMENTAL => 4,
        };
    }

    /**
     * Retourne la catégorie du diplôme (ELITE, NATIONALE, REGIONAL, DEPARTEMENTAL).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::ARBITRE_ELITE => 'ELITE',
            self::ARBITRE_NATIONALE => 'NATIONALE',
            self::ARBITRE_REGIONAL => 'REGIONAL',
            self::ARBITRE_DEPARTEMENTAL => 'DEPARTEMENTAL',
        };
    }

    /**
     * Vérifie si le diplôme est de niveau Elite.
     */
    public function isElite(): bool
    {
        return $this->getCategory() === 'ELITE';
    }

    /**
     * Vérifie si le diplôme est de niveau Nationale.
     */
    public function isNationale(): bool
    {
        return $this->getCategory() === 'NATIONALE';
    }

    /**
     * Vérifie si le diplôme est de niveau Régional.
     */
    public function isRegional(): bool
    {
        return $this->getCategory() === 'REGIONAL';
    }

    /**
     * Vérifie si le diplôme est de niveau Départemental.
     */
    public function isDepartemental(): bool
    {
        return $this->getCategory() === 'DEPARTEMENTAL';
    }

    /**
     * Vérifie si le diplôme permet d'arbitrer au niveau international.
     */
    public function canRefereeInternational(): bool
    {
        return $this->getLevel() <= 2; // Elite et Nationale
    }

    /**
     * Vérifie si le diplôme permet d'arbitrer au niveau national.
     */
    public function canRefereeNational(): bool
    {
        return $this->getLevel() <= 3; // Elite, Nationale, Régional
    }

    /**
     * Vérifie si le diplôme permet d'arbitrer au niveau régional.
     */
    public function canRefereeRegional(): bool
    {
        return $this->getLevel() <= 4; // Tous les niveaux
    }

    /**
     * Vérifie si le diplôme permet d'arbitrer au niveau départemental.
     */
    public function canRefereeDepartemental(): bool
    {
        return $this->getLevel() <= 4; // Tous les niveaux
    }

    /**
     * Retourne le niveau de compétition maximal que ce diplôme permet d'arbitrer.
     */
    public function getMaxCompetitionLevel(): string
    {
        return match ($this) {
            self::ARBITRE_ELITE => 'International',
            self::ARBITRE_NATIONALE => 'National',
            self::ARBITRE_REGIONAL => 'Régional',
            self::ARBITRE_DEPARTEMENTAL => 'Départemental',
        };
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::ARBITRE_ELITE => 1,
            self::ARBITRE_NATIONALE => 2,
            self::ARBITRE_REGIONAL => 3,
            self::ARBITRE_DEPARTEMENTAL => 4,
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
     * Retourne un tableau de choix groupés par niveau.
     */
    public static function getChoicesByLevel(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $level = $case->getLevel();
            if (!isset($choices[$level])) {
                $choices[$level] = [];
            }
            $choices[$level][$case->getLabel()] = $case->value;
        }
        \ksort($choices);

        return $choices;
    }

    /**
     * Retourne un tableau de choix groupés par catégorie.
     */
    public static function getChoicesByCategory(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $category = $case->getCategory();
            if (!isset($choices[$category])) {
                $choices[$category] = [];
            }
            $choices[$category][$case->getLabel()] = $case->value;
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
     * Retourne les diplômes ayant un niveau minimum donné.
     */
    public static function getDiplomasForLevel(int $minLevel): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->getLevel() >= $minLevel
        );
    }

    /**
     * Retourne les diplômes d'une catégorie donnée.
     */
    public static function getDiplomasForCategory(string $category): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->getCategory() === $category
        );
    }

    /**
     * Retourne les diplômes de niveau Elite.
     */
    public static function getEliteDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isElite()
        );
    }

    /**
     * Retourne les diplômes de niveau Nationale.
     */
    public static function getNationaleDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isNationale()
        );
    }

    /**
     * Retourne les diplômes de niveau Régional.
     */
    public static function getRegionalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isRegional()
        );
    }

    /**
     * Retourne les diplômes de niveau Départemental.
     */
    public static function getDepartementalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isDepartemental()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::ARBITRE_REGIONAL;
    }

    /**
     * Retourne les diplômes disponibles.
     */
    public static function getAvailableDiplomas(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de diplômes disponibles.
     */
    public static function getCount(): int
    {
        return count(self::cases());
    }

    /**
     * Vérifie si le diplôme est le diplôme par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }
}
