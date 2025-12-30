<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Entity\Announcement;
use App\Entity\AnnouncementResponse;
use App\Entity\User;
use App\Enums\ResponseStatus;
use App\Exception\AnnouncementException;

/**
 * Interface pour la gestion des réponses aux annonces.
 */
interface AnnouncementResponseManagerInterface
{
    /**
     * Répond à une annonce.
     *
     * @throws AnnouncementException Si la réponse échoue
     */
    public function respondToAnnouncement(
        Announcement $announcement,
        User $user,
        string $message,
        ?string $attachmentPath = null
    ): AnnouncementResponse;

    /**
     * Change le statut d'une réponse.
     *
     * @throws AnnouncementException Si le changement de statut échoue
     */
    public function changeResponseStatus(
        AnnouncementResponse $response,
        ResponseStatus $newStatus
    ): void;

    /**
     * Vérifie si un utilisateur peut répondre à une annonce.
     */
    public function canUserRespondToAnnouncement(User $user, Announcement $announcement): bool;

    /**
     * Vérifie si un utilisateur a déjà répondu à une annonce.
     */
    public function hasUserAlreadyResponded(User $user, Announcement $announcement): bool;

    /**
     * Récupère les réponses d'un utilisateur.
     *
     * @return AnnouncementResponse[]
     */
    public function getUserResponses(User $user): array;

    /**
     * Récupère les réponses d'une annonce.
     *
     * @return AnnouncementResponse[]
     */
    public function getAnnouncementResponses(Announcement $announcement): array;
}
