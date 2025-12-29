<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelDivision: string
{
    // Divisions professionnelles masculines
    case LIQUI_MOLY_STARLIGUE = 'LIQUI_MOLY_STARLIGUE';
    case PROLIGUE = 'PROLIGUE';

    // Divisions professionnelles féminines
    case LIGUE_BUTAGAZ_ENERGIE = 'LIGUE_BUTAGAZ_ENERGIE';
    case D2_FEMININE = 'D2_FEMININE';

    // Divisions nationales (mixte)
    case NATIONALE_1_ELITE = 'NATIONALE_1_ELITE';
    case NATIONALE_1 = 'NATIONALE_1';
    case NATIONALE_2 = 'NATIONALE_2';
    case NATIONALE_3 = 'NATIONALE_3';

    // Divisions régionales et départementales
    case PRENATIONAL = 'PRENATIONAL';
    case EXCELLENCE_REGIONALE = 'EXCELLENCE_REGIONALE';
    case HONNEUR_REGIONALE = 'HONNEUR_REGIONALE';
    case DEPARTEMENTAL = 'DEPARTEMENTAL';

    /**
     * Retourne le libellé complet de la division.
     */
    public function getLabel(): string
    {
        return match ($this) {
            // Professionnel masculin
            self::LIQUI_MOLY_STARLIGUE => 'Liqui Moly StarLigue',
            self::PROLIGUE => 'ProLigue',

            // Professionnel féminin
            self::LIGUE_BUTAGAZ_ENERGIE => 'Ligue Butagaz Energie',
            self::D2_FEMININE => 'D2 Féminine',

            // National
            self::NATIONALE_1_ELITE => 'Nationale 1 Elite',
            self::NATIONALE_1 => 'Nationale 1',
            self::NATIONALE_2 => 'Nationale 2',
            self::NATIONALE_3 => 'Nationale 3',

            // Régional et départemental
            self::PRENATIONAL => 'Prénational',
            self::EXCELLENCE_REGIONALE => 'Excellence Régionale',
            self::HONNEUR_REGIONALE => 'Honneur Régionale',
            self::DEPARTEMENTAL => 'Départemental',
        };
    }

    /**
     * Retourne le niveau de la division (PROFESSIONNEL_1, PROFESSIONNEL_2, NATIONAL_*, etc.).
     */
    public function getLevel(): string
    {
        return match ($this) {
            self::LIQUI_MOLY_STARLIGUE,
            self::LIGUE_BUTAGAZ_ENERGIE => 'PROFESSIONNEL_1',

            self::PROLIGUE,
            self::D2_FEMININE => 'PROFESSIONNEL_2',

            self::NATIONALE_1_ELITE => 'NATIONAL_1_ELITE',
            self::NATIONALE_1 => 'NATIONAL_1',
            self::NATIONALE_2 => 'NATIONAL_2',
            self::NATIONALE_3 => 'NATIONAL_3',

            self::PRENATIONAL => 'PRENATIONAL',

            self::EXCELLENCE_REGIONALE => 'REGIONAL_EXCELLENCE',

            self::HONNEUR_REGIONALE => 'REGIONAL_HONNEUR',

            self::DEPARTEMENTAL => 'DEPARTEMENTAL',
        };
    }

    /**
     * Retourne le genre de la division (MASCULIN, FEMININ, ou null pour mixte).
     */
    public function getGender(): ?string
    {
        return match ($this) {
            self::LIQUI_MOLY_STARLIGUE,
            self::PROLIGUE => 'MASCULIN',

            self::LIGUE_BUTAGAZ_ENERGIE,
            self::D2_FEMININE => 'FEMININ',

            self::NATIONALE_1_ELITE,
            self::NATIONALE_1,
            self::NATIONALE_2,
            self::NATIONALE_3 => null, // Mixte

            self::PRENATIONAL,
            self::EXCELLENCE_REGIONALE,
            self::HONNEUR_REGIONALE,
            self::DEPARTEMENTAL => null, // Mixte ou non spécifié
        };
    }

    /**
     * Vérifie si la division est professionnelle.
     */
    public function isProfessional(): bool
    {
        return \in_array($this->getLevel(), ['PROFESSIONNEL_1', 'PROFESSIONNEL_2'], true);
    }

    /**
     * Vérifie si la division est nationale.
     */
    public function isNational(): bool
    {
        return \str_starts_with($this->getLevel(), 'NATIONAL_');
    }

    /**
     * Vérifie si la division est régionale.
     */
    public function isRegional(): bool
    {
        return \str_starts_with($this->getLevel(), 'REGIONAL_');
    }

    /**
     * Vérifie si la division est départementale.
     */
    public function isDepartmental(): bool
    {
        return $this->getLevel() === 'DEPARTEMENTAL';
    }

    /**
     * Vérifie si la division est prénationale.
     */
    public function isPrenational(): bool
    {
        return $this->getLevel() === 'PRENATIONAL';
    }

    /**
     * Vérifie si la division est masculine.
     */
    public function isMasculine(): bool
    {
        return $this->getGender() === 'MASCULIN';
    }

    /**
     * Vérifie si la division est féminine.
     */
    public function isFeminine(): bool
    {
        return $this->getGender() === 'FEMININ';
    }

    /**
     * Vérifie si la division est mixte.
     */
    public function isMixed(): bool
    {
        return $this->getGender() === null;
    }

    /**
     * Retourne l'ordre hiérarchique de la division (1 = plus haut, 10 = plus bas).
     */
    public function getLevelOrder(): int
    {
        return match ($this->getLevel()) {
            'PROFESSIONNEL_1' => 1,
            'PROFESSIONNEL_2' => 2,
            'NATIONAL_1_ELITE' => 3,
            'NATIONAL_1' => 4,
            'NATIONAL_2' => 5,
            'NATIONAL_3' => 6,
            'PRENATIONAL' => 7,
            'REGIONAL_EXCELLENCE' => 8,
            'REGIONAL_HONNEUR' => 9,
            'DEPARTEMENTAL' => 10,
            default => 11,
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

        return $choices;
    }

    /**
     * Retourne un tableau de choix groupés par genre.
     */
    public static function getChoicesByGender(): array
    {
        $choices = [
            'MASCULIN' => [],
            'FEMININ' => [],
            'MIXTE' => [],
        ];

        foreach (self::cases() as $case) {
            $gender = $case->getGender();
            $genderKey = $gender ?? 'MIXTE';
            $choices[$genderKey][$case->getLabel()] = $case->value;
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
     * Retourne les divisions d'un genre donné.
     */
    public static function getDivisionsForGender(?string $gender): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->getGender() === $gender
        );
    }

    /**
     * Retourne les divisions d'un niveau donné.
     */
    public static function getDivisionsForLevel(string $level): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->getLevel() === $level
        );
    }

    /**
     * Retourne les divisions professionnelles.
     */
    public static function getProfessionalDivisions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isProfessional()
        );
    }

    /**
     * Retourne les divisions nationales.
     */
    public static function getNationalDivisions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isNational()
        );
    }

    /**
     * Retourne les divisions régionales.
     */
    public static function getRegionalDivisions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isRegional()
        );
    }

    /**
     * Retourne la division par défaut.
     */
    public static function getDefault(): self
    {
        return self::NATIONALE_1;
    }

    /**
     * Retourne les divisions disponibles.
     */
    public static function getAvailableDivisions(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de divisions disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si la division est la division par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }

    /**
     * Retourne un tableau de choix triés par ordre hiérarchique.
     */
    public static function getChoicesByLevelOrder(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }
        \uasort($choices, fn ($a, $b) => self::from($a)->getLevelOrder() <=> self::from($b)->getLevelOrder());

        return $choices;
    }
}
