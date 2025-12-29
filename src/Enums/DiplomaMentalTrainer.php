<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaMentalTrainer: string
{
    // Diplômes universitaires spécialisés (niveau élevé)
    case MASTER_PSYCHOLOGIE_DU_SPORT = 'MASTER_PSYCHOLOGIE_DU_SPORT';
    case MASTER_STAPS = 'MASTER_STAPS';

    // Diplômes universitaires généraux
    case LICENCE_PSYCHOLOGIE = 'LICENCE_PSYCHOLOGIE';
    case DIPLOME_UNIVERSITAIRE = 'DIPLOME_UNIVERSITAIRE';

    // Certifications spécialisées
    case CERTIFICATION_INSEP = 'CERTIFICATION_INSEP';
    case DIPLOME_FEDERAL_PREPARATION_MENTALE = 'DIPLOME_FEDERAL_PREPARATION_MENTALE';

    // Autres
    case AUTRE = 'AUTRE';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::MASTER_PSYCHOLOGIE_DU_SPORT => 'Master Psychologie du Sport',
            self::MASTER_STAPS => 'Master STAPS',
            self::LICENCE_PSYCHOLOGIE => 'Licence Psychologie',
            self::DIPLOME_UNIVERSITAIRE => 'Diplôme Universitaire',
            self::CERTIFICATION_INSEP => 'Certification INSEP',
            self::DIPLOME_FEDERAL_PREPARATION_MENTALE => 'Diplôme Fédéral Préparation Mentale',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::MASTER_PSYCHOLOGIE_DU_SPORT => 'Master Psy Sport',
            self::MASTER_STAPS => 'Master STAPS',
            self::LICENCE_PSYCHOLOGIE => 'Licence Psy',
            self::DIPLOME_UNIVERSITAIRE => 'Dipl. Universitaire',
            self::CERTIFICATION_INSEP => 'Certif. INSEP',
            self::DIPLOME_FEDERAL_PREPARATION_MENTALE => 'Dipl. Fédéral',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le niveau du diplôme (0 pour Autre, 1-6 pour les diplômes reconnus).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::AUTRE => 0,
            self::DIPLOME_FEDERAL_PREPARATION_MENTALE => 1,
            self::DIPLOME_UNIVERSITAIRE => 2,
            self::LICENCE_PSYCHOLOGIE => 3,
            self::MASTER_STAPS => 4,
            self::CERTIFICATION_INSEP => 5,
            self::MASTER_PSYCHOLOGIE_DU_SPORT => 6,
        };
    }

    /**
     * Retourne la catégorie du diplôme (MASTER, UNIVERSITAIRE, SPECIALISE, AUTRE).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::MASTER_PSYCHOLOGIE_DU_SPORT,
            self::MASTER_STAPS => 'MASTER',

            self::LICENCE_PSYCHOLOGIE,
            self::DIPLOME_UNIVERSITAIRE => 'UNIVERSITAIRE',

            self::CERTIFICATION_INSEP,
            self::DIPLOME_FEDERAL_PREPARATION_MENTALE => 'SPECIALISE',

            self::AUTRE => 'AUTRE',
        };
    }

    /**
     * Vérifie si le diplôme est un Master.
     */
    public function isMaster(): bool
    {
        return $this->getCategory() === 'MASTER';
    }

    /**
     * Vérifie si le diplôme est universitaire (Master ou Licence/Diplôme universitaire).
     */
    public function isUniversity(): bool
    {
        return \in_array($this->getCategory(), ['MASTER', 'UNIVERSITAIRE'], true);
    }

    /**
     * Vérifie si le diplôme est spécialisé (certifications).
     */
    public function isSpecialized(): bool
    {
        return $this->getCategory() === 'SPECIALISE';
    }

    /**
     * Vérifie si le diplôme est "Autre".
     */
    public function isOther(): bool
    {
        return $this->getCategory() === 'AUTRE';
    }

    /**
     * Vérifie si le diplôme est spécialisé en psychologie du sport.
     */
    public function isSpecializedInSportPsychology(): bool
    {
        return $this === self::MASTER_PSYCHOLOGIE_DU_SPORT;
    }

    /**
     * Vérifie si le diplôme est spécialisé en STAPS.
     */
    public function isSpecializedInStaps(): bool
    {
        return $this === self::MASTER_STAPS;
    }

    /**
     * Vérifie si le diplôme est certifié par l'INSEP.
     */
    public function isInsepCertified(): bool
    {
        return $this === self::CERTIFICATION_INSEP;
    }

    /**
     * Vérifie si le diplôme est certifié par la fédération.
     */
    public function isFederallyCertified(): bool
    {
        return $this === self::DIPLOME_FEDERAL_PREPARATION_MENTALE;
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des professionnels.
     */
    public function canWorkWithProfessionals(): bool
    {
        return $this->getLevel() >= 4;
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des athlètes d'élite.
     */
    public function canWorkWithEliteAthletes(): bool
    {
        return $this->getLevel() >= 5;
    }

    /**
     * Vérifie si le diplôme permet de travailler avec des athlètes internationaux.
     */
    public function canWorkWithInternationalAthletes(): bool
    {
        return $this->getLevel() >= 6;
    }

    /**
     * Vérifie si le diplôme permet de fournir un entraînement mental.
     */
    public function canProvideMentalTraining(): bool
    {
        return $this->getLevel() >= 2;
    }

    /**
     * Vérifie si le diplôme permet de fournir un soutien psychologique.
     */
    public function canProvidePsychologicalSupport(): bool
    {
        return $this === self::MASTER_PSYCHOLOGIE_DU_SPORT || $this === self::LICENCE_PSYCHOLOGIE;
    }

    /**
     * Retourne le nombre minimum d'années d'expérience recommandé.
     */
    public function getMinExperienceYears(): int
    {
        return match ($this->getLevel()) {
            0 => 0,
            1 => 1,
            2 => 1,
            3 => 2,
            4 => 3,
            5 => 4,
            6 => 5,
            default => 0,
        };
    }

    /**
     * Retourne le niveau maximal d'athlète que ce diplôme permet d'accompagner.
     */
    public function getMaxAthleteLevel(): string
    {
        return match ($this->getLevel()) {
            2, 3 => 'Régional',
            4 => 'National',
            5 => 'Professionnel',
            6 => 'International',
            default => 'Amateur',
        };
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::MASTER_PSYCHOLOGIE_DU_SPORT => 1,
            self::CERTIFICATION_INSEP => 2,
            self::MASTER_STAPS => 3,
            self::LICENCE_PSYCHOLOGIE => 4,
            self::DIPLOME_UNIVERSITAIRE => 5,
            self::DIPLOME_FEDERAL_PREPARATION_MENTALE => 6,
            self::AUTRE => 7,
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
     * Retourne les diplômes de type Master.
     */
    public static function getMasterDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isMaster()
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
     * Retourne les diplômes spécialisés.
     */
    public static function getSpecializedDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isSpecialized()
        );
    }

    /**
     * Retourne les diplômes "Autre".
     */
    public static function getOtherDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isOther()
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
     * Retourne les diplômes permettant de travailler avec des athlètes internationaux.
     */
    public static function getInternationalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->canWorkWithInternationalAthletes()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::MASTER_STAPS;
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
