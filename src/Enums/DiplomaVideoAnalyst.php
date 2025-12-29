<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaVideoAnalyst: string
{
    // Masters
    case MASTER_STAPS_EOPS = 'MASTER_STAPS_EOPS';
    case MASTER_STAPS = 'MASTER_STAPS';

    // Diplômes Universitaires (DU)
    case DU_ANALYSTE_VIDEO = 'DU_ANALYSTE_VIDEO';
    case DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE = 'DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE';
    case DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL = 'DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL';
    case DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE = 'DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE';
    case DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS = 'DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS';
    case DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE = 'DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE';

    // Autres
    case AUTRE = 'AUTRE';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS => 'Master STAPS EOPS',
            self::MASTER_STAPS => 'Master STAPS',
            self::DU_ANALYSTE_VIDEO => 'DU Analyste vidéo',
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE => 'DU Analyste vidéo performance sportive',
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL => 'DU Expertise vidéo en sport collectif et individuel',
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE => 'DU Analyste vidéo et Sport Data Science',
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS => 'DU Analyse vidéo en sports collectifs',
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE => 'DU Analyse vidéo et performance sportive',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS => 'Master STAPS EOPS',
            self::MASTER_STAPS => 'Master STAPS',
            self::DU_ANALYSTE_VIDEO => 'DU Analyste vidéo',
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE => 'DU Analyste vidéo perf. sportive',
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL => 'DU Expertise vidéo',
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE => 'DU Analyste vidéo & Data Science',
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS => 'DU Analyse vidéo sports collectifs',
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE => 'DU Analyse vidéo & performance',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le niveau du diplôme (0 pour Autre, 1-8 pour les diplômes reconnus).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::AUTRE => 0,
            self::DU_ANALYSTE_VIDEO => 1,
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE => 2,
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL => 3,
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE => 4,
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS => 5,
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE => 6,
            self::MASTER_STAPS => 7,
            self::MASTER_STAPS_EOPS => 8,
        };
    }

    /**
     * Retourne la catégorie du diplôme (MASTER, DU, AUTRE).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS,
            self::MASTER_STAPS => 'MASTER',

            self::DU_ANALYSTE_VIDEO,
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE,
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL,
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE,
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS,
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE => 'DU',

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
     * Vérifie si le diplôme est un Diplôme Universitaire (DU).
     */
    public function isDu(): bool
    {
        return $this->getCategory() === 'DU';
    }

    /**
     * Vérifie si le diplôme est "Autre".
     */
    public function isAutre(): bool
    {
        return $this->getCategory() === 'AUTRE';
    }

    /**
     * Vérifie si le diplôme est universitaire (Master ou DU).
     */
    public function isUniversity(): bool
    {
        return \in_array($this->getCategory(), ['MASTER', 'DU'], true);
    }

    /**
     * Vérifie si le diplôme permet d'analyser des matchs professionnels.
     */
    public function canAnalyzeProfessionalMatches(): bool
    {
        return $this->getLevel() >= 5;
    }

    /**
     * Vérifie si le diplôme permet d'analyser des matchs internationaux.
     */
    public function canAnalyzeInternationalMatches(): bool
    {
        return $this->getLevel() >= 7;
    }

    /**
     * Vérifie si le diplôme permet d'utiliser des logiciels d'analyse vidéo.
     */
    public function canUseVideoAnalysisSoftware(): bool
    {
        return $this->getLevel() >= 2;
    }

    /**
     * Vérifie si le diplôme permet d'utiliser des outils de data science.
     */
    public function canUseDataScienceTools(): bool
    {
        return $this === self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE;
    }

    /**
     * Vérifie si le diplôme permet d'analyser des sports collectifs.
     */
    public function canAnalyzeTeamSports(): bool
    {
        return \in_array($this, [
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL,
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS,
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE,
            self::MASTER_STAPS,
            self::MASTER_STAPS_EOPS,
        ], true);
    }

    /**
     * Vérifie si le diplôme permet d'analyser des sports individuels.
     */
    public function canAnalyzeIndividualSports(): bool
    {
        return \in_array($this, [
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL,
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE,
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE,
            self::MASTER_STAPS,
            self::MASTER_STAPS_EOPS,
        ], true);
    }

    /**
     * Retourne le nombre minimum d'années d'expérience recommandé.
     */
    public function getMinExperienceYears(): int
    {
        return match ($this->getLevel()) {
            0 => 0,
            1, 2, 3 => 1,
            4, 5, 6 => 2,
            7, 8 => 3,
            default => 0,
        };
    }

    /**
     * Retourne le niveau maximal de match que ce diplôme permet d'analyser.
     */
    public function getMaxMatchLevel(): string
    {
        return match ($this->getLevel()) {
            0, 1, 2, 3 => 'Amateur/Régional',
            4, 5, 6 => 'National',
            7, 8 => 'Professionnel/International',
            default => 'Amateur/Régional',
        };
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::MASTER_STAPS_EOPS => 1,
            self::MASTER_STAPS => 2,
            self::DU_ANALYSTE_VIDEO_ET_SPORT_DATA_SCIENCE => 3,
            self::DU_EXPERTISE_VIDEO_EN_SPORT_COLLECTIF_ET_INDIVIDUEL => 4,
            self::DU_ANALYSE_VIDEO_ET_PERFORMANCE_SPORTIVE => 5,
            self::DU_ANALYSE_VIDEO_EN_SPORTS_COLLECTIFS => 6,
            self::DU_ANALYSTE_VIDEO_PERFORMANCE_SPORTIVE => 7,
            self::DU_ANALYSTE_VIDEO => 8,
            self::AUTRE => 9,
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
     * Retourne les diplômes de type DU.
     */
    public static function getDuDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isDu()
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
     * Retourne les diplômes "Autre".
     */
    public static function getOtherDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isAutre()
        );
    }

    /**
     * Retourne les diplômes permettant d'analyser des matchs professionnels.
     */
    public static function getProfessionalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->canAnalyzeProfessionalMatches()
        );
    }

    /**
     * Retourne les diplômes permettant d'analyser des matchs internationaux.
     */
    public static function getInternationalDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->canAnalyzeInternationalMatches()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::DU_ANALYSTE_VIDEO;
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
