<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Announcement;
use App\Entity\AnnouncementResponse;
use App\Entity\User;
use App\Enums\ResponseStatus;
use App\Exception\AnnouncementException;
use App\Repository\AnnouncementRepository;
use App\Services\Interface\AnnouncementManagerInterface;
use App\Services\Interface\AnnouncementResponseManagerInterface;
use App\Services\Interface\FavoriteManagerInterface;

/**
 * Service principal pour la gestion des annonces.
 *
 * Ce service agit comme une façade qui délègue aux services spécialisés.
 * Il maintient la compatibilité avec le code existant tout en utilisant
 * la nouvelle architecture découpée.
 */
class AnnouncementService
{
    public function __construct(
        private readonly AnnouncementRepository $announcementRepo,
        private readonly AnnouncementManagerInterface $announcementManager,
        private readonly AnnouncementResponseManagerInterface $responseManager,
        private readonly FavoriteManagerInterface $favoriteManager
    ) {
    }

    /**
     * Crée une nouvelle annonce avec une date d'expiration par défaut (90 jours).
     *
     * @throws AnnouncementException Si la création échoue
     */
    public function createAnnouncement(Announcement $announcement): void
    {
        $this->announcementManager->createAnnouncement($announcement);
    }

    /**
     * Met à jour une annonce existante.
     *
     * @throws AnnouncementException Si la mise à jour échoue
     */
    public function updateAnnouncement(Announcement $announcement): void
    {
        $this->announcementManager->updateAnnouncement($announcement);
    }

    /**
     * Ferme une annonce (change le statut à CLOSED).
     *
     * @throws AnnouncementException Si la fermeture échoue
     */
    public function closeAnnouncement(Announcement $announcement): void
    {
        $this->announcementManager->closeAnnouncement($announcement);
    }

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
    ): AnnouncementResponse {
        return $this->responseManager->respondToAnnouncement(
            $announcement,
            $user,
            $message,
            $attachmentPath
        );
    }

    /**
     * Incrémente le compteur de vues d'une annonce.
     */
    public function incrementViewCount(Announcement $announcement): void
    {
        $this->announcementManager->incrementViewCount($announcement);
    }

    /**
     * Récupère les annonces visibles pour un utilisateur.
     */
    public function getVisibleAnnouncements(
        User $user,
        array $filters = []
    ): array {
        return $this->announcementRepo->findVisibleForUser($user, $filters);
    }

    /**
     * Change le statut d'une réponse.
     *
     * @throws AnnouncementException Si le changement de statut échoue
     */
    public function changeResponseStatus(
        AnnouncementResponse $response,
        ResponseStatus $newStatus
    ): void {
        $this->responseManager->changeResponseStatus($response, $newStatus);
    }

    /**
     * Vérifie si un utilisateur peut répondre à une annonce.
     */
    public function canUserRespondToAnnouncement(User $user, Announcement $announcement): bool
    {
        return $this->responseManager->canUserRespondToAnnouncement($user, $announcement);
    }

    /**
     * Sauvegarde une annonce dans les favoris d'un utilisateur.
     *
     * @throws AnnouncementException Si l'annonce est déjà dans les favoris
     */
    public function saveAnnouncementToFavorites(
        Announcement $announcement,
        User $user,
        ?string $notes = null
    ): void {
        $this->favoriteManager->saveAnnouncementToFavorites($announcement, $user, $notes);
    }

    /**
     * Retire une annonce des favoris d'un utilisateur.
     *
     * @throws AnnouncementException Si l'annonce n'est pas dans les favoris
     */
    public function removeAnnouncementFromFavorites(Announcement $announcement, User $user): void
    {
        $this->favoriteManager->removeAnnouncementFromFavorites($announcement, $user);
    }

    /**
     * Récupère les annonces sauvegardées par un utilisateur.
     *
     * @return Announcement[]
     */
    public function getSavedAnnouncements(User $user): array
    {
        return $this->favoriteManager->getSavedAnnouncements($user);
    }

    /**
     * Vérifie si une annonce est dans les favoris d'un utilisateur.
     */
    public function isAnnouncementSavedByUser(Announcement $announcement, User $user): bool
    {
        return $this->favoriteManager->isAnnouncementSavedByUser($announcement, $user);
    }

    /**
     * Récupère le gestionnaire d'annonces.
     */
    public function getAnnouncementManager(): AnnouncementManagerInterface
    {
        return $this->announcementManager;
    }

    /**
     * Récupère le gestionnaire de réponses.
     */
    public function getResponseManager(): AnnouncementResponseManagerInterface
    {
        return $this->responseManager;
    }

    /**
     * Récupère le gestionnaire de favoris.
     */
    public function getFavoriteManager(): FavoriteManagerInterface
    {
        return $this->favoriteManager;
    }

    /**
     * Vérifie si une annonce est expirée.
     */
    public function isAnnouncementExpired(Announcement $announcement): bool
    {
        return $this->announcementManager->isAnnouncementExpired($announcement);
    }

    /**
     * Vérifie si une annonce est active.
     */
    public function isAnnouncementActive(Announcement $announcement): bool
    {
        return $this->announcementManager->isAnnouncementActive($announcement);
    }

    /**
     * Vérifie si un utilisateur a déjà répondu à une annonce.
     */
    public function hasUserAlreadyResponded(User $user, Announcement $announcement): bool
    {
        return $this->responseManager->hasUserAlreadyResponded($user, $announcement);
    }

    /**
     * Compte le nombre d'annonces sauvegardées par un utilisateur.
     */
    public function countSavedAnnouncements(User $user): int
    {
        return $this->favoriteManager->countSavedAnnouncements($user);
    }

    /**
     * Récupère les notes associées à une annonce favorite.
     */
    public function getFavoriteNotes(Announcement $announcement, User $user): ?string
    {
        return $this->favoriteManager->getFavoriteNotes($announcement, $user);
    }
}
