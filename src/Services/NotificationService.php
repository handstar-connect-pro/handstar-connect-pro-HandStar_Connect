<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Announcement;
use App\Entity\AnnouncementResponse;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class NotificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private NotificationRepository $notificationRepo
    ) {
    }

    /**
     * Crée une notification simple.
     */
    public function createNotification(
        User $user,
        string $title,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?array $metadata = null
    ): Notification {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setActionUrl($actionUrl);
        $notification->setActionLabel($actionLabel);
        $notification->setMetadata($metadata);

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * Notifie un utilisateur d'une nouvelle réponse à son annonce.
     */
    public function notifyNewResponse(AnnouncementResponse $response): void
    {
        $announcement = $response->getAnnouncement();
        $responder = $response->getUser();
        $announcementOwner = $this->getAnnouncementOwner($announcement);

        if (!$announcementOwner) {
            return; // Pas de propriétaire à notifier
        }

        $title = 'Nouvelle réponse à votre annonce';
        $message = \sprintf(
            '%s a répondu à votre annonce "%s".',
            $responder->getDisplayName(),
            $announcement->getOfferTitle()
        );

        $actionUrl = \sprintf('/annonces/%d/reponses', $announcement->getId());
        $actionLabel = 'Voir la réponse';

        $metadata = [
            'announcement_id' => $announcement->getId(),
            'response_id' => $response->getId(),
            'responder_id' => $responder->getId(),
            'responder_name' => $responder->getDisplayName(),
        ];

        $this->createNotification(
            $announcementOwner,
            $title,
            $message,
            'response',
            $actionUrl,
            $actionLabel,
            $metadata
        );
    }

    /**
     * Notifie un candidat du changement de statut de sa réponse.
     */
    public function notifyResponseStatusChanged(AnnouncementResponse $response): void
    {
        $announcement = $response->getAnnouncement();
        $candidate = $response->getUser();
        $status = $response->getStatus();

        $statusLabels = [
            'pending' => 'en attente',
            'viewed' => 'vue',
            'shortlisted' => 'présélectionnée',
            'interview' => 'en entretien',
            'accepted' => 'acceptée',
            'rejected' => 'refusée',
            'withdrawn' => 'retirée',
        ];

        $statusLabel = $statusLabels[$status->value] ?? $status->value;

        $title = 'Statut de votre candidature mis à jour';
        $message = \sprintf(
            'Votre réponse à l\'annonce "%s" est maintenant %s.',
            $announcement->getOfferTitle(),
            $statusLabel
        );

        $actionUrl = \sprintf('/mes-candidatures/%d', $response->getId());
        $actionLabel = 'Voir les détails';

        $metadata = [
            'announcement_id' => $announcement->getId(),
            'response_id' => $response->getId(),
            'new_status' => $status->value,
            'announcement_title' => $announcement->getOfferTitle(),
        ];

        $this->createNotification(
            $candidate,
            $title,
            $message,
            'response',
            $actionUrl,
            $actionLabel,
            $metadata
        );
    }

    /**
     * Notifie les candidats qu'une annonce est fermée.
     */
    public function notifyAnnouncementClosed(AnnouncementResponse $response): void
    {
        $announcement = $response->getAnnouncement();
        $candidate = $response->getUser();

        $title = 'Annonce fermée';
        $message = \sprintf(
            'L\'annonce "%s" à laquelle vous avez répondu a été fermée.',
            $announcement->getOfferTitle()
        );

        $actionUrl = \sprintf('/annonces/%d', $announcement->getId());
        $actionLabel = 'Voir l\'annonce';

        $metadata = [
            'announcement_id' => $announcement->getId(),
            'response_id' => $response->getId(),
            'announcement_title' => $announcement->getOfferTitle(),
        ];

        $this->createNotification(
            $candidate,
            $title,
            $message,
            'announcement',
            $actionUrl,
            $actionLabel,
            $metadata
        );
    }

    /**
     * Notifie les utilisateurs concernés par une nouvelle annonce
     * (Optionnel - à utiliser avec parcimonie pour éviter le spam).
     */
    public function notifyNewAnnouncement(Announcement $announcement, array $interestedUsers): void
    {
        $title = 'Nouvelle annonce correspondant à votre profil';
        $message = \sprintf(
            'Une nouvelle annonce "%s" a été publiée et pourrait vous intéresser.',
            $announcement->getOfferTitle()
        );

        $actionUrl = \sprintf('/annonces/%d', $announcement->getId());
        $actionLabel = 'Voir l\'annonce';

        $metadata = [
            'announcement_id' => $announcement->getId(),
            'announcement_title' => $announcement->getOfferTitle(),
            'announcement_type' => $announcement->getOfferType()?->value,
            'announcement_profil' => $announcement->getOfferUserProfil()?->value,
        ];

        foreach ($interestedUsers as $user) {
            $this->createNotification(
                $user,
                $title,
                $message,
                'announcement',
                $actionUrl,
                $actionLabel,
                $metadata
            );
        }
    }

    /**
     * Récupère les notifications non lues d'un utilisateur.
     */
    public function getUnreadNotifications(User $user): array
    {
        return $this->notificationRepo->findUnreadByUser($user);
    }

    /**
     * Récupère les notifications récentes d'un utilisateur.
     */
    public function getRecentNotifications(User $user, int $limit = 10): array
    {
        return $this->notificationRepo->findRecentByUser($user, $limit);
    }

    /**
     * Compte les notifications non lues d'un utilisateur.
     */
    public function countUnreadNotifications(User $user): int
    {
        return $this->notificationRepo->countUnreadByUser($user);
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues.
     */
    public function markAllAsRead(User $user): int
    {
        return $this->notificationRepo->markAllAsReadByUser($user);
    }

    /**
     * Marque une notification spécifique comme lue.
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
        $this->em->flush();
    }

    /**
     * Supprime les notifications anciennes.
     */
    public function cleanupOldNotifications(int $days = 30): int
    {
        return $this->notificationRepo->deleteOldNotifications($days);
    }

    /**
     * Trouve le propriétaire d'une annonce
     * (À adapter selon la relation réelle entre User et Announcement).
     */
    private function getAnnouncementOwner(Announcement $announcement): ?User
    {
        // Note: Cette méthode nécessite que Announcement ait une relation avec User
        // Pour l'instant, on retourne null - à implémenter quand la relation sera définie
        // Exemple d'implémentation quand la relation existe :
        // return $announcement->getUser();

        return null;
    }
}
