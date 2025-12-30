<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Entity\Announcement;
use App\Exception\AnnouncementException;

/**
 * Interface pour la gestion des annonces (création, mise à jour, fermeture).
 */
interface AnnouncementManagerInterface
{
    /**
     * Crée une nouvelle annonce avec une date d'expiration par défaut (90 jours).
     *
     * @throws AnnouncementException Si la création échoue
     */
    public function createAnnouncement(Announcement $announcement): void;

    /**
     * Met à jour une annonce existante.
     *
     * @throws AnnouncementException Si la mise à jour échoue
     */
    public function updateAnnouncement(Announcement $announcement): void;

    /**
     * Ferme une annonce (change le statut à CLOSED).
     *
     * @throws AnnouncementException Si la fermeture échoue
     */
    public function closeAnnouncement(Announcement $announcement): void;

    /**
     * Incrémente le compteur de vues d'une annonce.
     */
    public function incrementViewCount(Announcement $announcement): void;

    /**
     * Vérifie si une annonce est expirée.
     */
    public function isAnnouncementExpired(Announcement $announcement): bool;

    /**
     * Vérifie si une annonce est active.
     */
    public function isAnnouncementActive(Announcement $announcement): bool;
}
