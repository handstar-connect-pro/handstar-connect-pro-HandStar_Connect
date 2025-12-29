<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactFonction: string
{
    case PRESIDENT = 'PRESIDENT';
    case VICE_PRESIDENT = 'VICE_PRESIDENT';
    case SECRETARY = 'SECRETARY';
    case SECRETARY_ASSISTANT = 'SECRETARY_ASSISTANT';
    case TREASURER = 'TREASURER';
    case TREASURER_ASSISTANT = 'TREASURER_ASSISTANT';
    case BUREAU_MEMBER = 'BUREAU_MEMBER';
    case BOARD_MEMBER = 'BOARD_MEMBER';
    case GENERAL_DIRECTOR = 'GENERAL_DIRECTOR';
    case DEPUTY_DIRECTOR = 'DEPUTY_DIRECTOR';
    case COMMUNICATION_MANAGER = 'COMMUNICATION_MANAGER';
    case EVENTS_MANAGER = 'EVENTS_MANAGER';
    case PARTNERSHIPS_MANAGER = 'PARTNERSHIPS_MANAGER';
    case OTHER = 'OTHER';

    /**
     * Retourne le libellé complet de la fonction.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PRESIDENT => 'Président',
            self::VICE_PRESIDENT => 'Vice-Président',
            self::SECRETARY => 'Secrétaire',
            self::SECRETARY_ASSISTANT => 'Secrétaire Adjoint',
            self::TREASURER => 'Trésorier',
            self::TREASURER_ASSISTANT => 'Trésorier Adjoint',
            self::BUREAU_MEMBER => 'Membre du Bureau',
            self::BOARD_MEMBER => 'Membre du Conseil d\'Administration',
            self::GENERAL_DIRECTOR => 'Directeur Général',
            self::DEPUTY_DIRECTOR => 'Directeur Adjoint',
            self::COMMUNICATION_MANAGER => 'Responsable Communication',
            self::EVENTS_MANAGER => 'Responsable Événements',
            self::PARTNERSHIPS_MANAGER => 'Responsable Partenariats',
            self::OTHER => 'Autre',
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
     * Retourne la fonction par défaut.
     */
    public static function getDefault(): self
    {
        return self::OTHER;
    }

    /**
     * Retourne le libellé court de la fonction.
     */
    public function getShortLabel(): string
    {
        return match ($this) {
            self::PRESIDENT => 'Prés.',
            self::VICE_PRESIDENT => 'Vice-Prés.',
            self::SECRETARY => 'Secr.',
            self::SECRETARY_ASSISTANT => 'Secr. Adj.',
            self::TREASURER => 'Trés.',
            self::TREASURER_ASSISTANT => 'Trés. Adj.',
            self::BUREAU_MEMBER => 'Memb. Bureau',
            self::BOARD_MEMBER => 'Memb. CA',
            self::GENERAL_DIRECTOR => 'Dir. Gén.',
            self::DEPUTY_DIRECTOR => 'Dir. Adj.',
            self::COMMUNICATION_MANAGER => 'Resp. Com.',
            self::EVENTS_MANAGER => 'Resp. Évén.',
            self::PARTNERSHIPS_MANAGER => 'Resp. Part.',
            self::OTHER => 'Autre',
        };
    }

    /**
     * Retourne la priorité d'affichage de la fonction.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::PRESIDENT => 1,
            self::VICE_PRESIDENT => 2,
            self::GENERAL_DIRECTOR => 3,
            self::DEPUTY_DIRECTOR => 4,
            self::SECRETARY => 5,
            self::SECRETARY_ASSISTANT => 6,
            self::TREASURER => 7,
            self::TREASURER_ASSISTANT => 8,
            self::COMMUNICATION_MANAGER => 9,
            self::EVENTS_MANAGER => 10,
            self::PARTNERSHIPS_MANAGER => 11,
            self::BUREAU_MEMBER => 12,
            self::BOARD_MEMBER => 13,
            self::OTHER => 99,
        };
    }

    /**
     * Vérifie si la fonction est une fonction de direction.
     */
    public function isLeadership(): bool
    {
        return \in_array($this, [
            self::PRESIDENT,
            self::VICE_PRESIDENT,
            self::GENERAL_DIRECTOR,
            self::DEPUTY_DIRECTOR,
        ], true);
    }

    /**
     * Vérifie si la fonction est une fonction de bureau.
     */
    public function isBureau(): bool
    {
        return \in_array($this, [
            self::PRESIDENT,
            self::VICE_PRESIDENT,
            self::SECRETARY,
            self::SECRETARY_ASSISTANT,
            self::TREASURER,
            self::TREASURER_ASSISTANT,
            self::BUREAU_MEMBER,
        ], true);
    }

    /**
     * Vérifie si la fonction est une fonction de conseil d'administration.
     */
    public function isBoard(): bool
    {
        return \in_array($this, [
            self::BOARD_MEMBER,
        ], true);
    }

    /**
     * Vérifie si la fonction est une fonction de management.
     */
    public function isManagement(): bool
    {
        return \in_array($this, [
            self::GENERAL_DIRECTOR,
            self::DEPUTY_DIRECTOR,
            self::COMMUNICATION_MANAGER,
            self::EVENTS_MANAGER,
            self::PARTNERSHIPS_MANAGER,
        ], true);
    }

    /**
     * Vérifie si la fonction est la fonction par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
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
     * Retourne les fonctions disponibles.
     */
    public static function getAvailableFunctions(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de fonctions disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Retourne les fonctions de direction.
     */
    public static function getLeadershipFunctions(): array
    {
        return [
            self::PRESIDENT,
            self::VICE_PRESIDENT,
            self::GENERAL_DIRECTOR,
            self::DEPUTY_DIRECTOR,
        ];
    }

    /**
     * Retourne les fonctions de bureau.
     */
    public static function getBureauFunctions(): array
    {
        return [
            self::PRESIDENT,
            self::VICE_PRESIDENT,
            self::SECRETARY,
            self::SECRETARY_ASSISTANT,
            self::TREASURER,
            self::TREASURER_ASSISTANT,
            self::BUREAU_MEMBER,
        ];
    }

    /**
     * Retourne les fonctions de management.
     */
    public static function getManagementFunctions(): array
    {
        return [
            self::GENERAL_DIRECTOR,
            self::DEPUTY_DIRECTOR,
            self::COMMUNICATION_MANAGER,
            self::EVENTS_MANAGER,
            self::PARTNERSHIPS_MANAGER,
        ];
    }
}
