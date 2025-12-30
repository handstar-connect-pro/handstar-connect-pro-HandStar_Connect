<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PersonGender;
use App\Enums\PlayerLaterality;
use App\Enums\PlayerPosition;
use App\Enums\LevelDivision;
use App\Enums\UserProfil;
use App\Enums\AnnouncementType;
use App\Enums\AnnouncementStatus;
use App\Enums\ResponseStatus;

class EnumValidationService
{
    /**
     * Valide une valeur pour l'énumération PersonGender.
     */
    public static function validatePersonGender(string $value): bool
    {
        return PersonGender::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération PlayerLaterality.
     */
    public static function validatePlayerLaterality(string $value): bool
    {
        return PlayerLaterality::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération PlayerPosition.
     */
    public static function validatePlayerPosition(string $value): bool
    {
        return PlayerPosition::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération LevelDivision.
     */
    public static function validateLevelDivision(string $value): bool
    {
        return LevelDivision::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération UserProfil.
     */
    public static function validateUserProfil(string $value): bool
    {
        return UserProfil::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération AnnouncementType.
     */
    public static function validateAnnouncementType(string $value): bool
    {
        return AnnouncementType::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération AnnouncementStatus.
     */
    public static function validateAnnouncementStatus(string $value): bool
    {
        return AnnouncementStatus::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour l'énumération ResponseStatus.
     */
    public static function validateResponseStatus(string $value): bool
    {
        return ResponseStatus::tryFrom($value) !== null;
    }

    /**
     * Valide une valeur pour une énumération spécifique.
     *
     * @param string $enumClass La classe de l'énumération
     * @param string $value La valeur à valider
     */
    public static function validateEnum(string $enumClass, string $value): bool
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("La classe $enumClass n'est pas une énumération valide.");
        }

        return $enumClass::tryFrom($value) !== null;
    }

    /**
     * Retourne toutes les valeurs valides pour une énumération.
     *
     * @param string $enumClass La classe de l'énumération
     * @return array<string> Les valeurs valides
     */
    public static function getValidValues(string $enumClass): array
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("La classe $enumClass n'est pas une énumération valide.");
        }

        return array_column($enumClass::cases(), 'value');
    }

    /**
     * Valide et retourne une instance d'énumération.
     *
     * @param string $enumClass La classe de l'énumération
     * @param string $value La valeur à valider
     * @return mixed L'instance de l'énumération
     * @throws \InvalidArgumentException Si la valeur n'est pas valide
     */
    public static function getValidatedEnum(string $enumClass, string $value)
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("La classe $enumClass n'est pas une énumération valide.");
        }

        $enum = $enumClass::tryFrom($value);
        if ($enum === null) {
            $validValues = implode(', ', self::getValidValues($enumClass));
            throw new \InvalidArgumentException(
                "La valeur '$value' n'est pas valide pour l'énumération $enumClass. Valeurs valides : $validValues"
            );
        }

        return $enum;
    }

    /**
     * Valide un tableau de valeurs pour une énumération.
     *
     * @param string $enumClass La classe de l'énumération
     * @param array<string> $values Les valeurs à valider
     * @return array<mixed> Les instances d'énumération validées
     * @throws \InvalidArgumentException Si une valeur n'est pas valide
     */
    public static function validateEnumArray(string $enumClass, array $values): array
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = self::getValidatedEnum($enumClass, $value);
        }
        return $result;
    }

    /**
     * Vérifie si toutes les valeurs d'un tableau sont valides pour une énumération.
     */
    public static function validateAllEnumValues(string $enumClass, array $values): bool
    {
        foreach ($values as $value) {
            if ($enumClass::tryFrom($value) === null) {
                return false;
            }
        }
        return true;
    }

    /**
     * Retourne les valeurs par défaut pour chaque énumération.
     */
    public static function getDefaultValues(): array
    {
        return [
            PersonGender::class => PersonGender::MALE->value,
            PlayerLaterality::class => PlayerLaterality::RIGHT_HANDED->value,
            PlayerPosition::class => PlayerPosition::CENTER_BACK->value,
            LevelDivision::class => LevelDivision::NATIONALE_1->value,
            UserProfil::class => UserProfil::PLAYER->value,
            AnnouncementType::class => AnnouncementType::PLAYER_SEARCH->value,
            AnnouncementStatus::class => AnnouncementStatus::PENDING->value,
            ResponseStatus::class => ResponseStatus::PENDING->value,
        ];
    }

    /**
     * Retourne la valeur par défaut pour une énumération spécifique.
     */
    public static function getDefaultValue(string $enumClass): string
    {
        $defaults = self::getDefaultValues();

        if (!isset($defaults[$enumClass])) {
            throw new \InvalidArgumentException("Aucune valeur par défaut définie pour l'énumération $enumClass.");
        }

        return $defaults[$enumClass];
    }

    /**
     * Génère un message d'erreur détaillé pour une valeur d'énumération invalide.
     */
    public static function getErrorMessage(string $enumClass, string $invalidValue): string
    {
        $validValues = self::getValidValues($enumClass);
        $enumName = basename(str_replace('\\', '/', $enumClass));

        return sprintf(
            "La valeur '%s' n'est pas valide pour l'énumération %s. Valeurs valides : %s",
            $invalidValue,
            $enumName,
            implode(', ', $validValues)
        );
    }
}
