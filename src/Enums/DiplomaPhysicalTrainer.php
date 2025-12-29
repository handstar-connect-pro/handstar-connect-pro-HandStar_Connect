<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaPhysicalTrainer: string
{
    // Diplômes de niveau universitaire
    case MASTER_STAPS_EOPS = 'MASTER_STAPS_EOPS';
    case LICENCE_STAPS = 'LICENCE_STAPS';

    // Diplômes universitaires spécialisés
    case DUEPP = 'DUEPP';
    case DU_CEP_COMETTI_DIJON = 'DU_CEP_COMETTI_DIJON';

    // Diplômes fédéraux
    case MONITORAT_FEDERAL = 'MONITORAT_FEDERAL';

    // Diplômes internationaux
    case NSCA_CSCS = 'NSCA_CSCS';

    // Diplômes d'État (JEPS)
    case DESJEPS = 'DESJEPS';
    case DEJEPS = 'DEJEPS';
    case BPJEPS = 'BPJEPS';

    // Autres diplômes
    case AUTRE = 'AUTRE';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS => 'Master STAPS EOPS',
            self::LICENCE_STAPS => 'Licence STAPS',
            self::DUEPP => 'DUEPP',
            self::DU_CEP_COMETTI_DIJON => 'DU CEP (Cometti, Dijon)',
            self::MONITORAT_FEDERAL => 'Monitorat Fédéral',
            self::NSCA_CSCS => 'NSCA (CSCS)',
            self::DESJEPS => 'DESJEPS',
            self::DEJEPS => 'DEJEPS',
            self::BPJEPS => 'BPJEPS',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS => 'Master STAPS',
            self::LICENCE_STAPS => 'Licence STAPS',
            self::DUEPP => 'DUEPP',
            self::DU_CEP_COMETTI_DIJON => 'DU CEP',
            self::MONITORAT_FEDERAL => 'Monitorat Fédéral',
            self::NSCA_CSCS => 'NSCA CSCS',
            self::DESJEPS => 'DESJEPS',
            self::DEJEPS => 'DEJEPS',
            self::BPJEPS => 'BPJEPS',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le niveau du diplôme (0 pour Autre, 1-7 pour les diplômes reconnus).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::AUTRE => 0,
            self::BPJEPS => 1,
            self::MONITORAT_FEDERAL => 2,
            self::DEJEPS => 3,
            self::LICENCE_STAPS => 4,
            self::DUEPP => 4,
            self::DU_CEP_COMETTI_DIJON => 4,
            self::DESJEPS => 5,
            self::MASTER_STAPS_EOPS => 6,
            self::NSCA_CSCS => 7, // Certification internationale reconnue
        };
    }

    /**
     * Retourne la catégorie du diplôme (UNIVERSITAIRE, UNIVERSITAIRE_SPECIALISE, FEDERAL, INTERNATIONAL, JEPS, AUTRE).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS,
            self::LICENCE_STAPS => 'UNIVERSITAIRE',

            self::DUEPP,
            self::DU_CEP_COMETTI_DIJON => 'UNIVERSITAIRE_SPECIALISE',

            self::MONITORAT_FEDERAL => 'FEDERAL',

            self::NSCA_CSCS => 'INTERNATIONAL',

            self::DESJEPS,
            self::DEJEPS,
            self::BPJEPS => 'JEPS',
            self::AUTRE => 'AUTRE',
        };
    }

    /**
     * Vérifie si le diplôme est universitaire.
     */
    public function isUniversity(): bool
    {
        return \in_array($this->getCategory(), ['UNIVERSITAIRE', 'UNIVERSITAIRE_SPECIALISE'], true);
    }

    /**
     * Vérifie si le diplôme est un JEPS.
     */
    public function isJeps(): bool
    {
        return $this->getCategory() === 'JEPS';
    }

    /**
     * Vérifie si le diplôme est fédéral.
     */
    public function isFederal(): bool
    {
        return $this->getCategory() === 'FEDERAL';
    }

    /**
     * Vérifie si le diplôme est international.
     */
    public function isInternational(): bool
    {
        return $this->getCategory() === 'INTERNATIONAL';
    }

    /**
     * Vérifie si le diplôme est spécialisé (universitaire spécialisé).
     */
    public function isSpecialized(): bool
    {
        return $this->getCategory() === 'UNIVERSITAIRE_SPECIALISE';
    }

    /**
     * Vérifie si le diplôme est "Autre".
     */
    public function isAutre(): bool
    {
        return $this->getCategory() === 'AUTRE';
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des professionnels.
     */
    public function canWorkWithProfessionals(): bool
    {
        return $this->getLevel() >= 5;
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des athlètes d'élite.
     */
    public function canWorkWithEliteAthletes(): bool
    {
        return $this->getLevel() >= 6;
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des athlètes internationaux.
     */
    public function canWorkWithInternationalAthletes(): bool
    {
        return $this->getLevel() >= 7;
    }

    /**
     * Vérifie si le diplôme permet de concevoir des programmes d'entraînement.
     */
    public function canDesignTrainingPrograms(): bool
    {
        return $this->getLevel() >= 3;
    }

    /**
     * Vérifie si le diplôme permet de superviser l'entraînement.
     */
    public function canSuperviseTraining(): bool
    {
        return $this->getLevel() >= 2;
    }

    /**
     * Retourne le nombre minimum d'années d'expérience recommandé.
     */
    public function getMinExperienceYears(): int
    {
        return match ($this->getLevel()) {
            1, 2 => 0,
            3, 4 => 2,
            5, 6 => 3,
            7 => 5,
            default => 0,
        };
    }

    /**
     * Retourne le niveau maximal d'athlète que ce diplôme permet d'accompagner.
     */
    public function getMaxAthleteLevel(): string
    {
        return match ($this->getLevel()) {
            1, 2 => 'Amateur',
            3, 4 => 'Régional',
            5 => 'National',
            6 => 'Professionnel',
            7 => 'International',
            default => 'Amateur',
        };
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::NSCA_CSCS => 1,
            self::MASTER_STAPS_EOPS => 2,
            self::DESJEPS => 3,
            self::DU_CEP_COMETTI_DIJON => 4,
            self::DUEPP => 5,
            self::LICENCE_STAPS => 6,
            self::DEJEPS => 7,
            self::MONITORAT_FEDERAL => 8,
            self::BPJEPS => 9,
            self::AUTRE => 10,
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
     * Retourne les diplômes universitaires.
     */
    public static function getUniversityDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isUniversity()
        );
    }

    /**
     * Retourne les diplômes JEPS.
     */
    public static function getJepsDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isJeps()
        );
    }

    /**
     * Retourne les diplômes internationaux.
     */
    public static function getInternationalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isInternational()
        );
    }

    /**
     * Retourne les diplômes permettant de travailler avec des professionnels.
     */
    public static function getProfessionalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->canWorkWithProfessionals()
        );
    }

    /**
     * Retourne les diplômes permettant de travailler avec des athlètes d'élite.
     */
    public static function getEliteDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->canWorkWithEliteAthletes()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::LICENCE_STAPS;
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
        return \count(self::cases());
    }

    /**
     * Vérifie si le diplôme est le diplôme par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }
}
