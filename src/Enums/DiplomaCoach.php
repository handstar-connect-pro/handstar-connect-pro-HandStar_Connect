<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaCoach: string
{
    case TFP_6_ENTRAINEUR_PROFESSIONNEL = 'TFP_6_ENTRAINEUR_PROFESSIONNEL';
    case TFP_5_ENTRAINEUR = 'TFP_5_ENTRAINEUR';
    case BP_JEPS_SPORTS_COLLECTIFS = 'BP_JEPS_SPORTS_COLLECTIFS';
    case TFP_4_EDUCATEUR = 'TFP_4_EDUCATEUR';
    case AUTRE = 'AUTRE';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::TFP_6_ENTRAINEUR_PROFESSIONNEL => 'TFP 6 – Entraîneur du secteur professionnel',
            self::TFP_5_ENTRAINEUR => 'TFP 5 – Entraîneur de Handball',
            self::BP_JEPS_SPORTS_COLLECTIFS => 'BP JEPS – Sports Collectifs (Handball)',
            self::TFP_4_EDUCATEUR => 'TFP 4 – Éducateur de Handball',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::TFP_6_ENTRAINEUR_PROFESSIONNEL => 'TFP 6 Professionnel',
            self::TFP_5_ENTRAINEUR => 'TFP 5 Entraîneur',
            self::BP_JEPS_SPORTS_COLLECTIFS => 'BP JEPS Handball',
            self::TFP_4_EDUCATEUR => 'TFP 4 Éducateur',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le niveau du diplôme (0 pour Autre, 4-6 pour les diplômes reconnus).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::AUTRE => 0,
            self::TFP_4_EDUCATEUR => 4,
            self::BP_JEPS_SPORTS_COLLECTIFS => 4,
            self::TFP_5_ENTRAINEUR => 5,
            self::TFP_6_ENTRAINEUR_PROFESSIONNEL => 6,
        };
    }

    /**
     * Retourne la catégorie du diplôme (TFP, JEPS, AUTRE).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::TFP_4_EDUCATEUR,
            self::TFP_5_ENTRAINEUR,
            self::TFP_6_ENTRAINEUR_PROFESSIONNEL => 'TFP',

            self::BP_JEPS_SPORTS_COLLECTIFS => 'JEPS',

            self::AUTRE => 'AUTRE',
        };
    }

    /**
     * Vérifie si le diplôme est un TFP.
     */
    public function isTfp(): bool
    {
        return $this->getCategory() === 'TFP';
    }

    /**
     * Vérifie si le diplôme est un JEPS.
     */
    public function isJeps(): bool
    {
        return $this->getCategory() === 'JEPS';
    }

    /**
     * Vérifie si le diplôme est un diplôme EHF (toujours faux pour les diplômes français).
     */
    public function isEhf(): bool
    {
        return false;
    }

    /**
     * Vérifie si le diplôme est "Autre".
     */
    public function isAutre(): bool
    {
        return $this->getCategory() === 'AUTRE';
    }

    /**
     * Vérifie si le diplôme est de niveau éducateur (niveau 4).
     */
    public function isEducatorLevel(): bool
    {
        return $this->getLevel() === 4;
    }

    /**
     * Vérifie si le diplôme est de niveau entraîneur (niveau 5).
     */
    public function isTrainerLevel(): bool
    {
        return $this->getLevel() === 5;
    }

    /**
     * Vérifie si le diplôme est de niveau professionnel (niveau 6+).
     */
    public function isProfessionalLevel(): bool
    {
        return $this->getLevel() >= 6;
    }

    /**
     * Vérifie si le diplôme est de niveau international (toujours faux pour les diplômes français).
     */
    public function isInternationalLevel(): bool
    {
        return false;
    }

    /**
     * Vérifie si le diplôme permet d'entraîner des jeunes.
     */
    public function canCoachYouth(): bool
    {
        return $this->getLevel() >= 4;
    }

    /**
     * Vérifie si le diplôme permet d'entraîner des adultes.
     */
    public function canCoachAdults(): bool
    {
        return $this->getLevel() >= 5;
    }

    /**
     * Vérifie si le diplôme permet d'entraîner au niveau professionnel.
     */
    public function canCoachProfessional(): bool
    {
        return $this->getLevel() >= 6;
    }

    /**
     * Vérifie si le diplôme permet d'entraîner au niveau international (toujours faux).
     */
    public function canCoachInternational(): bool
    {
        return false;
    }

    /**
     * Retourne la catégorie d'âge minimale que ce diplôme permet d'entraîner.
     */
    public function getMinAgeCategory(): string
    {
        return match ($this->getLevel()) {
            4 => 'U11',
            5 => 'U15',
            6 => 'Sénior',
            default => 'U11',
        };
    }

    /**
     * Retourne le niveau de division maximal que ce diplôme permet d'entraîner.
     */
    public function getMaxDivisionLevel(): string
    {
        return match ($this->getLevel()) {
            4 => 'Départemental',
            5 => 'Régional',
            6 => 'National',
            default => 'Départemental',
        };
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::TFP_6_ENTRAINEUR_PROFESSIONNEL => 1,
            self::TFP_5_ENTRAINEUR => 2,
            self::BP_JEPS_SPORTS_COLLECTIFS => 3,
            self::TFP_4_EDUCATEUR => 4,
            self::AUTRE => 5,
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
        ksort($choices);

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
        uasort($choices, fn ($a, $b) => self::from($a)->getPriority() <=> self::from($b)->getPriority());

        return $choices;
    }

    /**
     * Retourne un tableau de toutes les valeurs possibles.
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Vérifie si une valeur est valide pour cet enum.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::getValues(), true);
    }

    /**
     * Retourne les diplômes ayant un niveau minimum donné.
     */
    public static function getDiplomasForLevel(int $minLevel): array
    {
        return array_filter(
            self::cases(),
            fn ($case) => $case->getLevel() >= $minLevel
        );
    }

    /**
     * Retourne les diplômes d'une catégorie donnée.
     */
    public static function getDiplomasForCategory(string $category): array
    {
        return array_filter(
            self::cases(),
            fn ($case) => $case->getCategory() === $category
        );
    }

    /**
     * Retourne les diplômes professionnels.
     */
    public static function getProfessionalDiplomas(): array
    {
        return array_filter(
            self::cases(),
            fn ($case) => $case->isProfessionalLevel()
        );
    }

    /**
     * Retourne les diplômes internationaux (toujours vide pour les diplômes français).
     */
    public static function getInternationalDiplomas(): array
    {
        return array_filter(
            self::cases(),
            fn ($case) => $case->isInternationalLevel()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::TFP_5_ENTRAINEUR;
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
