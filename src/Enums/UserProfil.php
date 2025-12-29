<?php

declare(strict_types=1);

namespace App\Enums;

enum UserProfil: string
{
    case CLUB = 'CLUB';
    case TECHNICAL_DIRECTOR = 'TECHNICAL_DIRECTOR';
    case COACH = 'COACH';
    case GOALKEEPER_COACH = 'GOALKEEPER_COACH';
    case PLAYER = 'PLAYER';
    case PHYSICAL_TRAINER = 'PHYSICAL_TRAINER';
    case MENTAL_TRAINER = 'MENTAL_TRAINER';
    case PHYSIOTHERAPIST = 'PHYSIOTHERAPIST';
    case VIDEO_ANALYST = 'VIDEO_ANALYST';
    case REFEREE = 'REFEREE';

    /**
     * Retourne le libellé complet du type de profil.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::CLUB => 'Club',
            self::TECHNICAL_DIRECTOR => 'Directeur Technique',
            self::COACH => 'Entraîneur',
            self::GOALKEEPER_COACH => 'Entraîneur des gardiens',
            self::PLAYER => 'Joueur',
            self::PHYSICAL_TRAINER => 'Préparateur Physique',
            self::MENTAL_TRAINER => 'Préparateur Mental',
            self::PHYSIOTHERAPIST => 'Kinésithérapeute',
            self::VIDEO_ANALYST => 'Analyste Vidéo',
            self::REFEREE => 'Arbitre',
        };
    }

    /**
     * Retourne le libellé court du type de profil.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::CLUB => 'Club',
            self::TECHNICAL_DIRECTOR => 'Dir. Technique',
            self::COACH => 'Entraîneur',
            self::GOALKEEPER_COACH => 'Ent. Gardiens',
            self::PLAYER => 'Joueur',
            self::PHYSICAL_TRAINER => 'Prep. Physique',
            self::MENTAL_TRAINER => 'Prep. Mental',
            self::PHYSIOTHERAPIST => 'Kinésithérapeute',
            self::VIDEO_ANALYST => 'Analyste Vidéo',
            self::REFEREE => 'Arbitre',
        };
    }

    /**
     * Retourne la priorité d'affichage du type de profil.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::CLUB => 1,
            self::TECHNICAL_DIRECTOR => 2,
            self::COACH => 3,
            self::GOALKEEPER_COACH => 4,
            self::PLAYER => 5,
            self::PHYSICAL_TRAINER => 6,
            self::MENTAL_TRAINER => 7,
            self::PHYSIOTHERAPIST => 8,
            self::VIDEO_ANALYST => 9,
            self::REFEREE => 10,
        };
    }

    /**
     * Vérifie si le type de profil concerne les joueurs.
     */
    public function isPlayer(): bool
    {
        return $this === self::PLAYER;
    }

    /**
     * Vérifie si le type de profil concerne le staff technique.
     */
    public function isTechnicalStaff(): bool
    {
        return \in_array($this, [
            self::COACH,
            self::PHYSICAL_TRAINER,
            self::MENTAL_TRAINER,
            self::VIDEO_ANALYST,
            self::TECHNICAL_DIRECTOR,
            self::GOALKEEPER_COACH,
        ], true);
    }

    /**
     * Vérifie si le type de profil concerne le staff médical.
     */
    public function isMedicalStaff(): bool
    {
        return $this === self::PHYSIOTHERAPIST;
    }

    /**
     * Vérifie si le type de profil concerne les arbitres.
     */
    public function isReferee(): bool
    {
        return $this === self::REFEREE;
    }

    /**
     * Vérifie si le type de profil concerne les clubs.
     */
    public function isClub(): bool
    {
        return $this === self::CLUB;
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
     * Retourne le type de profil par défaut.
     */
    public static function getDefault(): self
    {
        return self::PLAYER;
    }

    /**
     * Retourne les types de profil disponibles.
     */
    public static function getAvailableTypes(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de types de profil disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si le type de profil est le type par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }

    /**
     * Retourne les types de profil de staff technique.
     */
    public static function getTechnicalStaffTypes(): array
    {
        return [
            self::COACH,
            self::PHYSICAL_TRAINER,
            self::MENTAL_TRAINER,
            self::VIDEO_ANALYST,
            self::TECHNICAL_DIRECTOR,
            self::GOALKEEPER_COACH,
        ];
    }

    /**
     * Retourne les types de profil de staff médical.
     */
    public static function getMedicalStaffTypes(): array
    {
        return [
            self::PHYSIOTHERAPIST,
        ];
    }

    /**
     * Retourne les types de profil d'arbitrage.
     */
    public static function getRefereeTypes(): array
    {
        return [
            self::REFEREE,
        ];
    }

    /**
     * Retourne les types de profil de club.
     */
    public static function getClubTypes(): array
    {
        return [
            self::CLUB,
        ];
    }
}
