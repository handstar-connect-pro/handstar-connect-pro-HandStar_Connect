<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Dto\CreateAnnouncementDto;
use App\Dto\RespondToAnnouncementDto;
use App\Dto\SaveToFavoritesDto;
use App\Entity\Announcement;
use App\Entity\User;
use App\Enums\AnnouncementStatus;
use App\Enums\AnnouncementType;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use App\Enums\UserProfil;
use App\Exception\AnnouncementValidationException;
use App\Services\ValidationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationServiceTest extends TestCase
{
    private ValidationService $validationService;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validationService = new ValidationService($this->validator);
    }

    public function testValidateDtoReturnsEmptyArrayWhenNoViolations(): void
    {
        $dto = new CreateAnnouncementDto();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $result = $this->validationService->validateDto($dto);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testValidateDtoReturnsViolationsWhenErrors(): void
    {
        $dto = new CreateAnnouncementDto();

        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn('offerTitle');
        $violation->method('getMessage')->willReturn('This value should not be blank.');
        $violation->method('getInvalidValue')->willReturn(null);

        $violationList = new ConstraintViolationList([$violation]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn($violationList);

        $result = $this->validationService->validateDto($dto);

        $this->assertCount(1, $result);
        $this->assertEquals('offerTitle', $result[0]['property']);
        $this->assertEquals('This value should not be blank.', $result[0]['message']);
    }

    public function testValidateDtoOrFailThrowsExceptionWhenViolations(): void
    {
        $dto = new CreateAnnouncementDto();

        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn('offerTitle');
        $violation->method('getMessage')->willReturn('This value should not be blank.');

        $violationList = new ConstraintViolationList([$violation]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn($violationList);

        $this->expectException(AnnouncementValidationException::class);
        $this->expectExceptionMessage('Validation failed: offerTitle: This value should not be blank.');

        $this->validationService->validateDtoOrFail($dto);
    }

    public function testValidateDtoOrFailDoesNotThrowWhenNoViolations(): void
    {
        $dto = new CreateAnnouncementDto();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        // Should not throw an exception
        $this->validationService->validateDtoOrFail($dto);

        $this->assertTrue(true); // Just to have an assertion
    }

    public function testValidateCreateAnnouncement(): void
    {
        $data = [
            'offerType' => AnnouncementType::OFFER,
            'offerTitle' => 'Test Announcement',
            'offerDescription' => 'This is a test announcement description.',
            'offerUserProfil' => UserProfil::PLAYER,
            'positionSought' => 'Goalkeeper',
            'leagueConcerned' => LevelDivision::NATIONAL_1,
            'location' => ListRegion::AUVERGNE_RHONE_ALPES,
        ];

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $dto = $this->validationService->validateCreateAnnouncement($data);

        $this->assertInstanceOf(CreateAnnouncementDto::class, $dto);
        $this->assertEquals(AnnouncementType::OFFER, $dto->offerType);
        $this->assertEquals('Test Announcement', $dto->offerTitle);
    }

    public function testValidateRespondToAnnouncement(): void
    {
        $data = [
            'message' => 'I am interested in this position.',
        ];

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $dto = $this->validationService->validateRespondToAnnouncement($data);

        $this->assertInstanceOf(RespondToAnnouncementDto::class, $dto);
        $this->assertEquals('I am interested in this position.', $dto->message);
    }

    public function testValidateSaveToFavorites(): void
    {
        $data = [
            'notes' => 'This looks interesting for next season.',
        ];

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $dto = $this->validationService->validateSaveToFavorites($data);

        $this->assertInstanceOf(SaveToFavoritesDto::class, $dto);
        $this->assertEquals('This looks interesting for next season.', $dto->notes);
    }

    public function testValidateEmail(): void
    {
        $this->assertTrue($this->validationService->validateEmail('test@example.com'));
        $this->assertTrue($this->validationService->validateEmail('user.name@domain.co.uk'));
        $this->assertFalse($this->validationService->validateEmail('invalid-email'));
        $this->assertFalse($this->validationService->validateEmail('@example.com'));
    }

    public function testValidateDate(): void
    {
        $this->assertTrue($this->validationService->validateDate('2023-12-31', 'Y-m-d'));
        $this->assertTrue($this->validationService->validateDate('31/12/2023', 'd/m/Y'));
        $this->assertFalse($this->validationService->validateDate('2023-13-01', 'Y-m-d')); // Invalid month
        $this->assertFalse($this->validationService->validateDate('not-a-date', 'Y-m-d'));
    }

    public function testValidateNumberInRange(): void
    {
        $this->assertTrue($this->validationService->validateNumberInRange(5, 1, 10));
        $this->assertTrue($this->validationService->validateNumberInRange(1, 1, 10)); // Min boundary
        $this->assertTrue($this->validationService->validateNumberInRange(10, 1, 10)); // Max boundary
        $this->assertFalse($this->validationService->validateNumberInRange(0, 1, 10));
        $this->assertFalse($this->validationService->validateNumberInRange(11, 1, 10));
    }

    public function testValidateStringLength(): void
    {
        $this->assertTrue($this->validationService->validateStringLength('test', 1, 10));
        $this->assertTrue($this->validationService->validateStringLength('a', 1, 10)); // Min boundary
        $this->assertTrue($this->validationService->validateStringLength('abcdefghij', 1, 10)); // Max boundary
        $this->assertFalse($this->validationService->validateStringLength('', 1, 10));
        $this->assertFalse($this->validationService->validateStringLength('abcdefghijk', 1, 10)); // Too long
    }

    public function testValidateUserCanRespondThrowsWhenAnnouncementNotActive(): void
    {
        $user = $this->createMock(User::class);
        $announcement = $this->createMock(Announcement::class);

        $announcement->method('isActive')->willReturn(false);

        $this->expectException(AnnouncementValidationException::class);
        $this->expectExceptionMessage('Cette annonce n\'est plus active');

        $this->validationService->validateUserCanRespond($user, $announcement);
    }

    public function testValidateUserCanRespondThrowsWhenAnnouncementExpired(): void
    {
        $user = $this->createMock(User::class);
        $announcement = $this->createMock(Announcement::class);

        $announcement->method('isActive')->willReturn(true);
        $announcement->method('isExpired')->willReturn(true);

        $this->expectException(AnnouncementValidationException::class);
        $this->expectExceptionMessage('Cette annonce est expirée');

        $this->validationService->validateUserCanRespond($user, $announcement);
    }

    public function testValidateAnnouncementCanBeSavedThrowsWhenAnnouncementNotActive(): void
    {
        $user = $this->createMock(User::class);
        $announcement = $this->createMock(Announcement::class);

        $announcement->method('isActive')->willReturn(false);

        $this->expectException(AnnouncementValidationException::class);
        $this->expectExceptionMessage('Cette annonce n\'est plus active');

        $this->validationService->validateAnnouncementCanBeSaved($announcement, $user);
    }

    public function testValidateAnnouncementCanBeSavedThrowsWhenAnnouncementExpired(): void
    {
        $user = $this->createMock(User::class);
        $announcement = $this->createMock(Announcement::class);

        $announcement->method('isActive')->willReturn(true);
        $announcement->method('isExpired')->willReturn(true);

        $this->expectException(AnnouncementValidationException::class);
        $this->expectExceptionMessage('Cette annonce est expirée');

        $this->validationService->validateAnnouncementCanBeSaved($announcement, $user);
    }
}
