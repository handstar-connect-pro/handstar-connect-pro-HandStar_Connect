<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CreateAnnouncementDto;
use App\Dto\RespondToAnnouncementDto;
use App\Dto\SaveToFavoritesDto;
use App\Entity\Announcement;
use App\Entity\User;
use App\Exception\AnnouncementValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service centralisé pour la validation des données.
 */
class ValidationService
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * Valide un DTO et retourne les violations.
     *
     * @return array Tableau des violations (vide si aucune)
     */
    public function validateDto(object $dto): array
    {
        $violations = $this->validator->validate($dto);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
                'invalidValue' => $violation->getInvalidValue(),
            ];
        }

        return $errors;
    }

    /**
     * Valide un DTO et lève une exception en cas d'erreur.
     *
     * @throws AnnouncementValidationException Si la validation échoue
     */
    public function validateDtoOrFail(object $dto): void
    {
        $errors = $this->validateDto($dto);

        if (!empty($errors)) {
            $messages = array_map(
                fn($error) => sprintf('%s: %s', $error['property'], $error['message']),
                $errors
            );

            throw new AnnouncementValidationException(
                'Validation failed: ' . implode(', ', $messages)
            );
        }
    }

    /**
     * Valide les données pour la création d'une annonce.
     *
     * @throws AnnouncementValidationException Si la validation échoue
     */
    public function validateCreateAnnouncement(array $data): CreateAnnouncementDto
    {
        $dto = CreateAnnouncementDto::fromArray($data);
        $this->validateDtoOrFail($dto);

        return $dto;
    }

    /**
     * Valide les données pour répondre à une annonce.
     *
     * @throws AnnouncementValidationException Si la validation échoue
     */
    public function validateRespondToAnnouncement(array $data): RespondToAnnouncementDto
    {
        $dto = RespondToAnnouncementDto::fromArray($data);
        $this->validateDtoOrFail($dto);

        return $dto;
    }

    /**
     * Valide les données pour sauvegarder une annonce dans les favoris.
     *
     * @throws AnnouncementValidationException Si la validation échoue
     */
    public function validateSaveToFavorites(array $data): SaveToFavoritesDto
    {
        $dto = SaveToFavoritesDto::fromArray($data);
        $this->validateDtoOrFail($dto);

        return $dto;
    }

    /**
     * Valide qu'un utilisateur peut répondre à une annonce.
     *
     * @throws AnnouncementValidationException Si l'utilisateur ne peut pas répondre
     */
    public function validateUserCanRespond(User $user, Announcement $announcement): void
    {
        // Vérifier que l'annonce est active
        if (!$announcement->isActive()) {
            throw new AnnouncementValidationException('Cette annonce n\'est plus active');
        }

        // Vérifier que l'annonce n'est pas expirée
        if ($announcement->isExpired()) {
            throw new AnnouncementValidationException('Cette annonce est expirée');
        }

        // Vérifier que l'utilisateur n'a pas déjà répondu
        // Note: Cette vérification nécessite une relation entre User et AnnouncementResponse
        // Pour l'instant, on suppose que c'est géré par le service de réponse
    }

    /**
     * Valide qu'une annonce peut être sauvegardée dans les favoris.
     *
     * @throws AnnouncementValidationException Si l'annonce ne peut pas être sauvegardée
     */
    public function validateAnnouncementCanBeSaved(Announcement $announcement, User $user): void
    {
        // Vérifier que l'annonce est active
        if (!$announcement->isActive()) {
            throw new AnnouncementValidationException('Cette annonce n\'est plus active');
        }

        // Vérifier que l'annonce n'est pas expirée
        if ($announcement->isExpired()) {
            throw new AnnouncementValidationException('Cette annonce est expirée');
        }

        // Vérifier que l'utilisateur n'a pas déjà sauvegardé cette annonce
        // Note: Cette vérification nécessite une relation entre User et SavedAnnouncement
        // Pour l'instant, on suppose que c'est géré par le service de favoris
    }

    /**
     * Valide les données d'un tableau selon des règles spécifiques.
     *
     * @param array $data Données à valider
     * @param array $rules Règles de validation au format Symfony
     *
     * @return array Tableau des violations (vide si aucune)
     */
    public function validateArray(array $data, array $rules): array
    {
        // Cette méthode pourrait être implémentée avec une bibliothèque de validation
        // Pour l'instant, on retourne un tableau vide
        // TODO: Implémenter la validation de tableau avec les règles Symfony

        return [];
    }

    /**
     * Valide une adresse email.
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide une date.
     */
    public function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Valide un nombre dans une plage.
     */
    public function validateNumberInRange(float $number, float $min, float $max): bool
    {
        return $number >= $min && $number <= $max;
    }

    /**
     * Valide une chaîne de caractères selon une longueur.
     */
    public function validateStringLength(string $string, int $min, int $max): bool
    {
        $length = mb_strlen($string);
        return $length >= $min && $length <= $max;
    }
}
