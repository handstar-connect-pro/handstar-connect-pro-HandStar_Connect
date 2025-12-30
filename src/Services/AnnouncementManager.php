<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Announcement;
use App\Enums\AnnouncementStatus;
use App\Exception\AnnouncementException;
use App\Exception\AnnouncementNotActiveException;
use App\Exception\AnnouncementExpiredException;
use App\Services\Interface\AnnouncementManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class AnnouncementManager implements AnnouncementManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * Crée une nouvelle annonce avec une date d'expiration par défaut (90 jours).
     *
     * @throws AnnouncementException Si la création échoue
     */
    public function createAnnouncement(Announcement $announcement): void
    {
        try {
            // Définir la date d'expiration par défaut (90 jours)
            if ($announcement->getExpiresAt() === null) {
                $announcement->setExpiresAt(
                    new \DateTimeImmutable()->modify('+90 days')
                );
            }

            // Définir le statut par défaut si non défini
            if ($announcement->getOfferStatus() === null) {
                $announcement->setOfferStatus(AnnouncementStatus::ACTIVE);
            }

            $this->em->persist($announcement);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la création de l\'annonce: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Met à jour une annonce existante.
     *
     * @throws AnnouncementException Si la mise à jour échoue
     */
    public function updateAnnouncement(Announcement $announcement): void
    {
        try {
            $announcement->setUpdatedAt(new \DateTimeImmutable());
            $this->em->flush();
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la mise à jour de l\'annonce: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Ferme une annonce (change le statut à CLOSED).
     *
     * @throws AnnouncementException Si la fermeture échoue
     */
    public function closeAnnouncement(Announcement $announcement): void
    {
        try {
            $announcement->setOfferStatus(AnnouncementStatus::CLOSED);
            $announcement->setUpdatedAt(new \DateTimeImmutable());
            $this->em->flush();
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la fermeture de l\'annonce: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Incrémente le compteur de vues d'une annonce.
     */
    public function incrementViewCount(Announcement $announcement): void
    {
        try {
            $announcement->incrementViewCount();
            $this->em->flush();
        } catch (\Exception $e) {
            // Log l'erreur mais ne la propage pas pour ne pas interrompre l'utilisateur
            error_log('Erreur lors de l\'incrémentation des vues: ' . $e->getMessage());
        }
    }

    /**
     * Vérifie si une annonce est expirée.
     */
    public function isAnnouncementExpired(Announcement $announcement): bool
    {
        return $announcement->isExpired();
    }

    /**
     * Vérifie si une annonce est active.
     */
    public function isAnnouncementActive(Announcement $announcement): bool
    {
        return $announcement->getOfferStatus() === AnnouncementStatus::ACTIVE;
    }

    /**
     * Valide une annonce avant création ou mise à jour.
     *
     * @throws AnnouncementNotActiveException Si l'annonce n'est pas active
     * @throws AnnouncementExpiredException Si l'annonce est expirée
     */
    public function validateAnnouncement(Announcement $announcement): void
    {
        if (!$this->isAnnouncementActive($announcement)) {
            throw new AnnouncementNotActiveException();
        }

        if ($this->isAnnouncementExpired($announcement)) {
            throw new AnnouncementExpiredException();
        }
    }

    /**
     * Récupère la date d'expiration par défaut (90 jours à partir de maintenant).
     */
    public function getDefaultExpirationDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable()->modify('+90 days');
    }

    /**
     * Vérifie si une annonce doit être renouvelée (expire dans moins de 7 jours).
     */
    public function needsRenewal(Announcement $announcement): bool
    {
        $expiresAt = $announcement->getExpiresAt();
        if ($expiresAt === null) {
            return false;
        }

        $sevenDaysFromNow = new \DateTimeImmutable()->modify('+7 days');
        return $expiresAt <= $sevenDaysFromNow;
    }

    /**
     * Renouvelle une annonce (ajoute 90 jours à la date d'expiration).
     *
     * @throws AnnouncementException Si le renouvellement échoue
     */
    public function renewAnnouncement(Announcement $announcement): void
    {
        try {
            $newExpirationDate = $announcement->getExpiresAt()?->modify('+90 days')
                ?? $this->getDefaultExpirationDate();

            $announcement->setExpiresAt($newExpirationDate);
            $announcement->setUpdatedAt(new \DateTimeImmutable());
            $this->em->flush();
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors du renouvellement de l\'annonce: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
