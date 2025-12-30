<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Entity\Announcement;
use App\Entity\User;
use App\Exception\AnnouncementException;

/**
 * Interface pour la gestion des annonces favorites.
 */
interface FavoriteManagerInterface
{
    /**
     * Sauvegarde une annonce dans les favoris d'un utilisateur.
     *
     * @throws AnnouncementException Si l'annonce est déjà dans les favoris
     */
    public function saveAnnouncementToFavorites(
        Announcement $announcement,
        User $user,
        ?string $notes = null
    ): void;

    /**
     * Retire une annonce des favoris d'un utilisateur.
     *
     * @throws AnnouncementException Si l'annonce n'est pas dans les favoris
     */
    public function removeAnnouncementFromFavorites(Announcement $announcement, User $user): void;

    /**
     * Récupère les annonces sauvegardées par un utilisateur.
     *
     * @return Announcement[]
     */
    public function getSavedAnnouncements(User $user): array;

    /**
     * Vérifie si une annonce est dans les favoris d'un utilisateur.
     */
    public function isAnnouncementSavedByUser(Announcement $announcement, User $user): bool;

    /**
     * Compte le nombre d'annonces sauvegardées par un utilisateur.
     */
    public function countSavedAnnouncements(User $user): int;

    /**
     * Récupère les notes associées à une annonce favorite.
     */
    public function getFavoriteNotes(Announcement $announcement, User $user): ?string;

    /**
     * Met à jour les notes d'une annonce favorite.
     */
    public function updateFavoriteNotes(Announcement $announcement, User $user, string $notes): void;
}
