<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Services\EnumValidationService;
use App\Enums\PersonGender;
use App\Enums\PlayerLaterality;
use App\Enums\PlayerPosition;
use App\Enums\LevelDivision;
use App\Enums\UserProfil;
use App\Enums\AnnouncementType;
use App\Enums\AnnouncementStatus;
use App\Enums\ResponseStatus;
use PHPUnit\Framework\TestCase;

class EnumValidationServiceTest extends TestCase
{
    /**
     * Teste la validation de PersonGender.
     */
    public function testValidatePersonGender(): void
    {
        // Valeurs valides
        $this->assertTrue(EnumValidationService::validatePersonGender('MALE'));
        $this->assertTrue(EnumValidationService::validatePersonGender('FEMALE'));

        // Valeurs invalides
        $this->assertFalse(EnumValidationService::validatePersonGender('male'));
        $this->assertFalse(EnumValidationService::validatePersonGender('female'));
        $this->assertFalse(EnumValidationService::validatePersonGender(''));
        $this->assertFalse(EnumValidationService::validatePersonGender('INVALID'));
    }

    /**
     * Teste la validation de PlayerLaterality.
     */
    public function testValidatePlayerLaterality(): void
    {
        // Valeurs valides
        $this->assertTrue(EnumValidationService::validatePlayerLaterality('RIGHT_HANDED'));
        $this->assertTrue(EnumValidationService::validatePlayerLaterality('LEFT_HANDED'));
        $this->assertTrue(EnumValidationService::validatePlayerLaterality('AMBIDEXTROUS'));

        // Valeurs invalides
        $this->assertFalse(EnumValidationService::validatePlayerLaterality('right'));
        $this->assertFalse(EnumValidationService::validatePlayerLaterality('left'));
        $this->assertFalse(EnumValidationService::validatePlayerLaterality(''));
        $this->assertFalse(EnumValidationService::validatePlayerLaterality('INVALID'));
    }

    /**
     * Teste la validation de PlayerPosition.
     */
    public function testValidatePlayerPosition(): void
    {
        // Valeurs valides
        $this->assertTrue(EnumValidationService::validatePlayerPosition('GOALKEEPER'));
        $this->assertTrue(EnumValidationService::validatePlayerPosition('LEFT_WING'));
        $this->assertTrue(EnumValidationService::validatePlayerPosition('CENTER_BACK'));

        // Valeurs invalides
        $this->assertFalse(EnumValidationService::validatePlayerPosition('goalkeeper'));
        $this->assertFalse(EnumValidationService::validatePlayerPosition(''));
        $this->assertFalse(EnumValidationService::validatePlayerPosition('INVALID'));
    }

    /**
     * Teste la validation de LevelDivision.
     */
    public function testValidateLevelDivision(): void
    {
        // Valeurs valides
        $this->assertTrue(EnumValidationService::validateLevelDivision('NATIONALE_1'));
        $this->assertTrue(EnumValidationService::validateLevelDivision('NATIONALE_2'));
        $this->assertTrue(EnumValidationService::validateLevelDivision('LIQUI_MOLY_STARLIGUE'));

        // Valeurs invalides
        $this->assertFalse(EnumValidationService::validateLevelDivision('national_1'));
        $this->assertFalse(EnumValidationService::validateLevelDivision(''));
        $this->assertFalse(EnumValidationService::validateLevelDivision('INVALID'));
    }

    /**
     * Teste la validation de UserProfil.
     */
    public function testValidateUserProfil(): void
    {
        // Valeurs valides
        $this->assertTrue(EnumValidationService::validateUserProfil('PLAYER'));
        $this->assertTrue(EnumValidationService::validateUserProfil('COACH'));
        $this->assertTrue(EnumValidationService::validateUserProfil('REFEREE'));

        // Valeurs invalides
        $this->assertFalse(EnumValidationService::validateUserProfil('player'));
        $this->assertFalse(EnumValidationService::validateUserProfil(''));
        $this->assertFalse(EnumValidationService::validateUserProfil('INVALID'));
    }

    /**
     * Teste la validation générique d'énumération.
     */
    public function testValidateEnum(): void
    {
        // Test avec une énumération valide
        $this->assertTrue(EnumValidationService::validateEnum(PersonGender::class, 'MALE'));
        $this->assertTrue(EnumValidationService::validateEnum(PlayerLaterality::class, 'RIGHT_HANDED'));

        // Test avec une énumération invalide
        $this->assertFalse(EnumValidationService::validateEnum(PersonGender::class, 'INVALID'));
        $this->assertFalse(EnumValidationService::validateEnum(PlayerLaterality::class, 'invalid'));

        // Test avec une classe qui n'est pas une énumération
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('n\'est pas une énumération valide');
        EnumValidationService::validateEnum(\stdClass::class, 'test');
    }

    /**
     * Teste la récupération des valeurs valides.
     */
    public function testGetValidValues(): void
    {
        $personGenderValues = EnumValidationService::getValidValues(PersonGender::class);
        $this->assertContains('MALE', $personGenderValues);
        $this->assertContains('FEMALE', $personGenderValues);
        $this->assertCount(2, $personGenderValues);

        $playerLateralityValues = EnumValidationService::getValidValues(PlayerLaterality::class);
        $this->assertContains('RIGHT_HANDED', $playerLateralityValues);
        $this->assertContains('LEFT_HANDED', $playerLateralityValues);
        $this->assertContains('AMBIDEXTROUS', $playerLateralityValues);
        $this->assertCount(3, $playerLateralityValues);

        // Test avec une classe qui n'est pas une énumération
        $this->expectException(\InvalidArgumentException::class);
        EnumValidationService::getValidValues(\stdClass::class);
    }

    /**
     * Teste la récupération d'une instance d'énumération validée.
     */
    public function testGetValidatedEnum(): void
    {
        // Test avec une valeur valide
        $enum = EnumValidationService::getValidatedEnum(PersonGender::class, 'MALE');
        $this->assertInstanceOf(PersonGender::class, $enum);
        $this->assertEquals('MALE', $enum->value);

        // Test avec une valeur invalide
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('n\'est pas valide');
        EnumValidationService::getValidatedEnum(PersonGender::class, 'INVALID');

        // Test avec une classe qui n'est pas une énumération
        $this->expectException(\InvalidArgumentException::class);
        EnumValidationService::getValidatedEnum(\stdClass::class, 'test');
    }

    /**
     * Teste la validation d'un tableau de valeurs.
     */
    public function testValidateEnumArray(): void
    {
        // Test avec des valeurs valides
        $values = ['MALE', 'FEMALE'];
        $enums = EnumValidationService::validateEnumArray(PersonGender::class, $values);

        $this->assertCount(2, $enums);
        $this->assertInstanceOf(PersonGender::class, $enums[0]);
        $this->assertInstanceOf(PersonGender::class, $enums[1]);
        $this->assertEquals('MALE', $enums[0]->value);
        $this->assertEquals('FEMALE', $enums[1]->value);

        // Test avec une valeur invalide
        $this->expectException(\InvalidArgumentException::class);
        EnumValidationService::validateEnumArray(PersonGender::class, ['MALE', 'INVALID']);
    }

    /**
     * Teste la validation de toutes les valeurs d'un tableau.
     */
    public function testValidateAllEnumValues(): void
    {
        // Test avec toutes les valeurs valides
        $this->assertTrue(EnumValidationService::validateAllEnumValues(
            PersonGender::class,
            ['MALE', 'FEMALE']
        ));

        // Test avec une valeur invalide
        $this->assertFalse(EnumValidationService::validateAllEnumValues(
            PersonGender::class,
            ['MALE', 'INVALID']
        ));

        // Test avec un tableau vide
        $this->assertTrue(EnumValidationService::validateAllEnumValues(
            PersonGender::class,
            []
        ));
    }

    /**
     * Teste la récupération des valeurs par défaut.
     */
    public function testGetDefaultValues(): void
    {
        $defaults = EnumValidationService::getDefaultValues();

        $this->assertArrayHasKey(PersonGender::class, $defaults);
        $this->assertEquals('MALE', $defaults[PersonGender::class]);

        $this->assertArrayHasKey(PlayerLaterality::class, $defaults);
        $this->assertEquals('RIGHT_HANDED', $defaults[PlayerLaterality::class]);

        $this->assertArrayHasKey(PlayerPosition::class, $defaults);
        $this->assertEquals('CENTER_BACK', $defaults[PlayerPosition::class]);

        $this->assertArrayHasKey(LevelDivision::class, $defaults);
        $this->assertEquals('NATIONALE_1', $defaults[LevelDivision::class]);
    }

    /**
     * Teste la récupération d'une valeur par défaut spécifique.
     */
    public function testGetDefaultValue(): void
    {
        $this->assertEquals('MALE', EnumValidationService::getDefaultValue(PersonGender::class));
        $this->assertEquals('RIGHT_HANDED', EnumValidationService::getDefaultValue(PlayerLaterality::class));
        $this->assertEquals('CENTER_BACK', EnumValidationService::getDefaultValue(PlayerPosition::class));
        $this->assertEquals('NATIONALE_1', EnumValidationService::getDefaultValue(LevelDivision::class));

        // Test avec une énumération sans valeur par défaut
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Aucune valeur par défaut définie');
        EnumValidationService::getDefaultValue(\stdClass::class);
    }

    /**
     * Teste la génération de messages d'erreur.
     */
    public function testGetErrorMessage(): void
    {
        $errorMessage = EnumValidationService::getErrorMessage(PersonGender::class, 'INVALID');

        $this->assertStringContainsString("La valeur 'INVALID'", $errorMessage);
        $this->assertStringContainsString("PersonGender", $errorMessage);
        $this->assertStringContainsString("MALE", $errorMessage);
        $this->assertStringContainsString("FEMALE", $errorMessage);
    }

    /**
     * Teste la validation de AnnouncementType.
     */
    public function testValidateAnnouncementType(): void
    {
        $this->assertTrue(EnumValidationService::validateAnnouncementType('PLAYER_SEARCH'));
        $this->assertTrue(EnumValidationService::validateAnnouncementType('COACH_SEARCH'));
        $this->assertFalse(EnumValidationService::validateAnnouncementType('invalid'));
    }

    /**
     * Teste la validation de AnnouncementStatus.
     */
    public function testValidateAnnouncementStatus(): void
    {
        $this->assertTrue(EnumValidationService::validateAnnouncementStatus('PENDING'));
        $this->assertTrue(EnumValidationService::validateAnnouncementStatus('PUBLISHED'));
        $this->assertFalse(EnumValidationService::validateAnnouncementStatus('invalid'));
    }

    /**
     * Teste la validation de ResponseStatus.
     */
    public function testValidateResponseStatus(): void
    {
        $this->assertTrue(EnumValidationService::validateResponseStatus('PENDING'));
        $this->assertTrue(EnumValidationService::validateResponseStatus('ACCEPTED'));
        $this->assertFalse(EnumValidationService::validateResponseStatus('invalid'));
    }

    /**
     * Teste la cohérence entre les méthodes spécifiques et génériques.
     */
    public function testConsistencyBetweenSpecificAndGenericMethods(): void
    {
        // PersonGender
        $this->assertEquals(
            EnumValidationService::validatePersonGender('MALE'),
            EnumValidationService::validateEnum(PersonGender::class, 'MALE')
        );

        // PlayerLaterality
        $this->assertEquals(
            EnumValidationService::validatePlayerLaterality('RIGHT_HANDED'),
            EnumValidationService::validateEnum(PlayerLaterality::class, 'RIGHT_HANDED')
        );

        // PlayerPosition
        $this->assertEquals(
            EnumValidationService::validatePlayerPosition('GOALKEEPER'),
            EnumValidationService::validateEnum(PlayerPosition::class, 'GOALKEEPER')
        );
    }

    /**
     * Teste les performances avec un grand nombre de validations.
     */
    public function testPerformance(): void
    {
        $startTime = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            EnumValidationService::validatePersonGender('MALE');
            EnumValidationService::validatePersonGender('FEMALE');
            EnumValidationService::validatePersonGender('INVALID');
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // La validation devrait être rapide (moins de 0.1 seconde pour 3000 validations)
        $this->assertLessThan(0.1, $executionTime, 'La validation des énumérations devrait être rapide');
    }
}
