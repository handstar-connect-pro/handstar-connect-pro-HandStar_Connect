<?php

declare(strict_types=1);

namespace App\Enums;

enum PlayerPosition: string
{
    case GOALKEEPER = 'GOALKEEPER';
    case LEFT_WING = 'LEFT_WING';
    case LEFT_BACK = 'LEFT_BACK';
    case CENTER_BACK = 'CENTER_BACK';
    case RIGHT_BACK = 'RIGHT_BACK';
    case RIGHT_WING = 'RIGHT_WING';
    case PIVOT = 'PIVOT';

    /**
     * Retourne le libellé complet de la position.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::GOALKEEPER => 'Gardien',
            self::LEFT_WING => 'Ailier Gauche',
            self::LEFT_BACK => 'Arrière Gauche',
            self::CENTER_BACK => 'Demi-Centre',
            self::RIGHT_BACK => 'Arrière Droit',
            self::RIGHT_WING => 'Ailier Droit',
            self::PIVOT => 'Pivot',
        };
    }

    /**
     * Retourne l'abréviation de la position.
     */
    public function getAbbreviation(): string
    {
        return match ($this) {
            self::GOALKEEPER => 'GK',
            self::LEFT_WING => 'LW',
            self::LEFT_BACK => 'LB',
            self::CENTER_BACK => 'CB',
            self::RIGHT_BACK => 'RB',
            self::RIGHT_WING => 'RW',
            self::PIVOT => 'PV',
        };
    }

    /**
     * Retourne la priorité d'affichage de la position.
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::GOALKEEPER => 1,
            self::LEFT_WING => 2,
            self::LEFT_BACK => 3,
            self::CENTER_BACK => 4,
            self::RIGHT_BACK => 5,
            self::RIGHT_WING => 6,
            self::PIVOT => 7,
        };
    }

    /**
     * Vérifie si la position est celle de gardien.
     */
    public function isGoalkeeper(): bool
    {
        return $this === self::GOALKEEPER;
    }

    /**
     * Vérifie si la position est celle d'un joueur de champ.
     */
    public function isFieldPlayer(): bool
    {
        return !$this->isGoalkeeper();
    }

    /**
     * Vérifie si la position est une position d'arrière.
     */
    public function isBackPosition(): bool
    {
        return \in_array($this, [self::LEFT_BACK, self::CENTER_BACK, self::RIGHT_BACK], true);
    }

    /**
     * Vérifie si la position est une position d'ailier.
     */
    public function isWingPosition(): bool
    {
        return \in_array($this, [self::LEFT_WING, self::RIGHT_WING], true);
    }

    /**
     * Vérifie si la position est celle de pivot.
     */
    public function isPivot(): bool
    {
        return $this === self::PIVOT;
    }

    /**
     * Retourne la zone de jeu de la position.
     */
    public function getZone(): string
    {
        return match ($this) {
            self::GOALKEEPER => 'goal',
            self::LEFT_WING, self::RIGHT_WING => 'wing',
            self::LEFT_BACK, self::RIGHT_BACK, self::CENTER_BACK => 'back',
            self::PIVOT => 'pivot',
        };
    }

    /**
     * Retourne le côté de la position (gauche, droite, centre).
     */
    public function getSide(): string
    {
        return match ($this) {
            self::LEFT_WING, self::LEFT_BACK => 'left',
            self::RIGHT_WING, self::RIGHT_BACK => 'right',
            self::GOALKEEPER, self::CENTER_BACK, self::PIVOT => 'center',
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
     * Retourne un tableau de choix groupés par zone.
     */
    public static function getChoicesByZone(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $zone = $case->getZone();
            if (!isset($choices[$zone])) {
                $choices[$zone] = [];
            }
            $choices[$zone][$case->getLabel()] = $case->value;
        }

        return $choices;
    }

    /**
     * Retourne un tableau de choix groupés par côté.
     */
    public static function getChoicesBySide(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $side = $case->getSide();
            if (!isset($choices[$side])) {
                $choices[$side] = [];
            }
            $choices[$side][$case->getLabel()] = $case->value;
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
     * Retourne la position par défaut.
     */
    public static function getDefault(): self
    {
        return self::CENTER_BACK;
    }

    /**
     * Retourne les positions disponibles.
     */
    public static function getAvailablePositions(): array
    {
        return self::cases();
    }

    /**
     * Retourne le nombre de positions disponibles.
     */
    public static function getCount(): int
    {
        return \count(self::cases());
    }

    /**
     * Vérifie si la position est la position par défaut.
     */
    public function isDefault(): bool
    {
        return $this === self::getDefault();
    }

    /**
     * Retourne les positions de champ (non gardien).
     */
    public static function getFieldPositions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isFieldPlayer()
        );
    }

    /**
     * Retourne les positions d'arrière.
     */
    public static function getBackPositions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isBackPosition()
        );
    }

    /**
     * Retourne les positions d'ailier.
     */
    public static function getWingPositions(): array
    {
        return \array_filter(
            self::cases(),
            fn ($case) => $case->isWingPosition()
        );
    }
}
