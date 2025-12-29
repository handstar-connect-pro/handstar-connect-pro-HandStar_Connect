<?php

declare(strict_types=1);

namespace App\Enums;

enum DiplomaPhysioTherapist: string
{
    case DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE = 'DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE';
    case DIPLOME_CADRE_SANTE = 'DIPLOME_CADRE_SANTE';
    case DU_DIU_SPECIALISATION = 'DU_DIU_SPECIALISATION';
    case AUTRE = 'AUTRE';

    /**
     * Retourne le libellé complet du diplôme.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE => 'Diplôme d\'État de masseur‑kinésithérapeute (DEMK)',
            self::DIPLOME_CADRE_SANTE => 'Diplôme de cadre de santé',
            self::DU_DIU_SPECIALISATION => 'DU / DIU de spécialisation',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le libellé court du diplôme.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE => 'DEMK',
            self::DIPLOME_CADRE_SANTE => 'Cadre de santé',
            self::DU_DIU_SPECIALISATION => 'DU/DIU Spécialisation',
            self::AUTRE => 'Autre',
        };
    }

    /**
     * Retourne le niveau du diplôme (0 pour Autre, 1-3 pour les diplômes reconnus).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE => 3,
            self::DIPLOME_CADRE_SANTE => 2,
            self::DU_DIU_SPECIALISATION => 1,
            self::AUTRE => 0,
        };
    }

    /**
     * Retourne la catégorie du diplôme (DIPLOME_ETAT, CADRE_SANTE, FORMATION_COMPLEMENTAIRE, AUTRE).
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE => 'DIPLOME_ETAT',
            self::DIPLOME_CADRE_SANTE => 'CADRE_SANTE',
            self::DU_DIU_SPECIALISATION => 'FORMATION_COMPLEMENTAIRE',
            self::AUTRE => 'AUTRE',
        };
    }

    /**
     * Vérifie si le diplôme est un diplôme d'État.
     */
    public function isDiplomeEtat(): bool
    {
        return $this->getCategory() === 'DIPLOME_ETAT';
    }

    /**
     * Vérifie si le diplôme est un diplôme de cadre de santé.
     */
    public function isCadreSante(): bool
    {
        return $this->getCategory() === 'CADRE_SANTE';
    }

    /**
     * Vérifie si le diplôme est une formation complémentaire.
     */
    public function isFormationComplementaire(): bool
    {
        return $this->getCategory() === 'FORMATION_COMPLEMENTAIRE';
    }

    /**
     * Vérifie si le diplôme est "Autre".
     */
    public function isAutre(): bool
    {
        return $this->getCategory() === 'AUTRE';
    }

    /**
     * Vérifie si le diplôme est de niveau le plus élevé.
     */
    public function isHighestLevel(): bool
    {
        return $this->getLevel() === 3;
    }

    /**
     * Vérifie si le diplôme est de niveau intermédiaire.
     */
    public function isIntermediateLevel(): bool
    {
        return $this->getLevel() === 2;
    }

    /**
     * Vérifie si le diplôme est de niveau spécialisation.
     */
    public function isSpecializationLevel(): bool
    {
        return $this->getLevel() === 1;
    }

    /**
     * Vérifie si le diplôme est de niveau "Autre".
     */
    public function isOtherLevel(): bool
    {
        return $this->getLevel() === 0;
    }

    /**
     * Retourne la priorité d'affichage du diplôme.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE => 1,
            self::DIPLOME_CADRE_SANTE => 2,
            self::DU_DIU_SPECIALISATION => 3,
            self::AUTRE => 4,
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
     * Retourne un tableau de choix groupés par niveau (du plus élevé au plus bas).
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
        \krsort($choices); // Niveau le plus élevé en premier

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
     * Retourne les diplômes de niveau le plus élevé.
     */
    public static function getHighestLevelDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isHighestLevel()
        );
    }

    /**
     * Retourne les diplômes d'État.
     */
    public static function getStateDiplomas(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isDiplomeEtat()
        );
    }

    /**
     * Retourne le diplôme par défaut.
     */
    public static function getDefault(): self
    {
        return self::DIPLOME_ETAT_MASSEUR_KINESITHERAPEUTE;
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
