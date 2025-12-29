<?php

declare(strict_types=1);

namespace App\Enums;

enum ListRegion: string
{
    case AUVERGNE_RHONE_ALPES = 'auvergne_rhone_alpes';
    case BOURGOGNE_FRANCHE_COMTE = 'bourgogne_franche_comte';
    case BRETAGNE = 'bretagne';
    case CENTRE_VAL_DE_LOIRE = 'centre_val_de_loire';
    case CORSE = 'corse';
    case GRAND_EST = 'grand_est';
    case HAUTS_DE_FRANCE = 'hauts_de_france';
    case ILE_DE_FRANCE = 'ile_de_france';
    case NORMANDIE = 'normandie';
    case NOUVELLE_AQUITAINE = 'nouvelle_aquitaine';
    case OCCITANIE = 'occitanie';
    case PAYS_DE_LA_LOIRE = 'pays_de_la_loire';
    case PROVENCE_ALPES_COTE_AZUR = 'provence_alpes_cote_azur';
    case GUADELOUPE = 'guadeloupe';
    case MARTINIQUE = 'martinique';
    case GUYANE = 'guyane';
    case REUNION = 'reunion';
    case MAYOTTE = 'mayotte';

    /**
     * Retourne le libellé complet de la région.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::AUVERGNE_RHONE_ALPES => 'Auvergne-Rhône-Alpes',
            self::BOURGOGNE_FRANCHE_COMTE => 'Bourgogne-Franche-Comté',
            self::BRETAGNE => 'Bretagne',
            self::CENTRE_VAL_DE_LOIRE => 'Centre-Val de Loire',
            self::CORSE => 'Corse',
            self::GRAND_EST => 'Grand Est',
            self::HAUTS_DE_FRANCE => 'Hauts-de-France',
            self::ILE_DE_FRANCE => 'Île-de-France',
            self::NORMANDIE => 'Normandie',
            self::NOUVELLE_AQUITAINE => 'Nouvelle-Aquitaine',
            self::OCCITANIE => 'Occitanie',
            self::PAYS_DE_LA_LOIRE => 'Pays de la Loire',
            self::PROVENCE_ALPES_COTE_AZUR => 'Provence-Alpes-Côte d\'Azur',
            self::GUADELOUPE => 'Guadeloupe',
            self::MARTINIQUE => 'Martinique',
            self::GUYANE => 'Guyane',
            self::REUNION => 'La Réunion',
            self::MAYOTTE => 'Mayotte',
        };
    }

    /**
     * Retourne le libellé court (code) de la région.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::AUVERGNE_RHONE_ALPES => 'ARA',
            self::BOURGOGNE_FRANCHE_COMTE => 'BFC',
            self::BRETAGNE => 'BRE',
            self::CENTRE_VAL_DE_LOIRE => 'CVL',
            self::CORSE => 'COR',
            self::GRAND_EST => 'GES',
            self::HAUTS_DE_FRANCE => 'HDF',
            self::ILE_DE_FRANCE => 'IDF',
            self::NORMANDIE => 'NOR',
            self::NOUVELLE_AQUITAINE => 'NAQ',
            self::OCCITANIE => 'OCC',
            self::PAYS_DE_LA_LOIRE => 'PDL',
            self::PROVENCE_ALPES_COTE_AZUR => 'PAC',
            self::GUADELOUPE => 'GUA',
            self::MARTINIQUE => 'MTQ',
            self::GUYANE => 'GUY',
            self::REUNION => 'REU',
            self::MAYOTTE => 'MYT',
        };
    }

    /**
     * Retourne la priorité d'affichage de la région
     * Priorité : régions métropolitaines d'abord, puis DOM.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::ILE_DE_FRANCE => 1,
            self::AUVERGNE_RHONE_ALPES => 2,
            self::NOUVELLE_AQUITAINE => 3,
            self::OCCITANIE => 4,
            self::HAUTS_DE_FRANCE => 5,
            self::GRAND_EST => 6,
            self::PAYS_DE_LA_LOIRE => 7,
            self::NORMANDIE => 8,
            self::BOURGOGNE_FRANCHE_COMTE => 9,
            self::BRETAGNE => 10,
            self::CENTRE_VAL_DE_LOIRE => 11,
            self::PROVENCE_ALPES_COTE_AZUR => 12,
            self::CORSE => 13,
            self::REUNION => 14,
            self::GUADELOUPE => 15,
            self::MARTINIQUE => 16,
            self::GUYANE => 17,
            self::MAYOTTE => 18,
        };
    }

    /**
     * Vérifie si la région est métropolitaine.
     */
    public function isMetropolitan(): bool
    {
        return match ($this) {
            self::GUADELOUPE,
            self::MARTINIQUE,
            self::GUYANE,
            self::REUNION,
            self::MAYOTTE => false,
            default => true,
        };
    }

    /**
     * Vérifie si la région est d'outre-mer.
     */
    public function isOverseas(): bool
    {
        return !$this->isMetropolitan();
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
     * Recherche une région par son code.
     */
    public static function findByCode(string $code): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $code) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Retourne toutes les régions métropolitaines.
     */
    public static function getMetropolitanRegions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isMetropolitan()
        );
    }

    /**
     * Retourne toutes les régions d'outre-mer.
     */
    public static function getOverseasRegions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isOverseas()
        );
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
     * Retourne un tableau de choix groupés par type (métropolitaines/outre-mer).
     */
    public static function getChoicesByMetropolitan(): array
    {
        $choices = [
            'Métropolitaines' => [],
            'Outre-mer' => [],
        ];
        foreach (self::cases() as $case) {
            $category = $case->isMetropolitan() ? 'Métropolitaines' : 'Outre-mer';
            $choices[$category][$case->getLabel()] = $case->value;
        }

        return $choices;
    }

    /**
     * Retourne la région par défaut.
     */
    public static function getDefault(): self
    {
        return self::ILE_DE_FRANCE;
    }

    /**
     * Retourne les régions disponibles.
     */
    public static function getAvailableRegions(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de régions disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si la région est la région par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }
}
