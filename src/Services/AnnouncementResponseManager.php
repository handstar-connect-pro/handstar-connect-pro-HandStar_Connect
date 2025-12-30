<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Announcement;
use App\Entity\AnnouncementResponse;
use App\Entity\User;
use App\Enums\AnnouncementStatus;
use App\Enums\ResponseStatus;
use App\Exception\AnnouncementException;
use App\Exception\AnnouncementNotActiveException;
use App\Exception\AnnouncementExpiredException;
use App\Exception\CannotRespondToAnnouncementException;
use App\Exception\AlreadyRespondedException;
use App\Repository\AnnouncementResponseRepository;
use App\Services\Interface\AnnouncementResponseManagerInterface;
use App\Services\Interface\AccessControlServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class AnnouncementResponseManager implements AnnouncementResponseManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AnnouncementResponseRepository $responseRepo,
        private readonly AccessControlServiceInterface $accessControlService,
        private readonly NotificationService $notificationService
    ) {
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
        try {
            // Vérifier que l'annonce est active
            if ($announcement->getOfferStatus() !== AnnouncementStatus::ACTIVE) {
                throw new AnnouncementNotActiveException();
            }

            // Vérifier que l'annonce n'est pas expirée
            if ($announcement->isExpired()) {
                throw new AnnouncementExpiredException();
            }

            // Vérifier que l'utilisateur peut répondre à cette annonce
            if (!$this->canUserRespondToAnnouncement($user, $announcement)) {
                throw new CannotRespondToAnnouncementException();
            }

            // Vérifier qu'il n'a pas déjà répondu
            if ($this->hasUserAlreadyResponded($user, $announcement)) {
                throw new AlreadyRespondedException();
            }

            $response = new AnnouncementResponse();
            $response->setAnnouncement($announcement);
            $response->setUser($user);
            $response->setMessage($message);
            $response->setAttachmentPath($attachmentPath);
            // Le statut est défini à PENDING par défaut dans le constructeur

            $this->em->persist($response);
            $this->em->flush();

            // Notifier l'auteur de l'annonce
            $this->notificationService->notifyNewResponse($response);

            return $response;
        } catch (AnnouncementException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la réponse à l\'annonce: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
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
        try {
            $response->setStatus($newStatus);
            $this->em->flush();

            // Notifier le candidat du changement de statut
            $this->notificationService->notifyResponseStatusChanged($response);
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors du changement de statut de la réponse: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Vérifie si un utilisateur peut répondre à une annonce.
     */
    public function canUserRespondToAnnouncement(User $user, Announcement $announcement): bool
    {
        $userProfil = $user->getProfil();
        $announcementProfil = $announcement->getOfferUserProfil();

        if ($userProfil === null || $announcementProfil === null) {
            return false;
        }

        return $this->accessControlService->canRespond($userProfil, $announcementProfil);
    }

    /**
     * Vérifie si un utilisateur a déjà répondu à une annonce.
     */
    public function hasUserAlreadyResponded(User $user, Announcement $announcement): bool
    {
        $existingResponse = $this->responseRepo->findOneBy([
            'announcement' => $announcement,
            'user' => $user,
        ]);

        return $existingResponse !== null;
    }

    /**
     * Récupère les réponses d'un utilisateur.
     *
     * @return AnnouncementResponse[]
     */
    public function getUserResponses(User $user): array
    {
        return $this->responseRepo->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère les réponses d'une annonce.
     *
     * @return AnnouncementResponse[]
     */
    public function getAnnouncementResponses(Announcement $announcement): array
    {
        return $announcement->getResponses()->toArray();
    }

    /**
     * Récupère une réponse spécifique par son ID.
     */
    public function getResponseById(int $responseId): ?AnnouncementResponse
    {
        return $this->responseRepo->find($responseId);
    }

    /**
     * Supprime une réponse.
     *
     * @throws AnnouncementException Si la suppression échoue
     */
    public function deleteResponse(AnnouncementResponse $response): void
    {
        try {
            $this->em->remove($response);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la suppression de la réponse: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Compte le nombre de réponses à une annonce.
     */
    public function countAnnouncementResponses(Announcement $announcement): int
    {
        return $this->responseRepo->count(['announcement' => $announcement]);
    }

    /**
     * Compte le nombre de réponses d'un utilisateur.
     */
    public function countUserResponses(User $user): int
    {
        return $this->responseRepo->count(['user' => $user]);
    }

    /**
     * Récupère les réponses par statut.
     *
     * @return AnnouncementResponse[]
     */
    public function getResponsesByStatus(ResponseStatus $status): array
    {
        return $this->responseRepo->findBy(
            ['status' => $status],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère les réponses en attente pour une annonce.
     *
     * @return AnnouncementResponse[]
     */
    public function getPendingResponses(Announcement $announcement): array
    {
        return array_filter(
            $announcement->getResponses()->toArray(),
            fn($response) => $response->isPending()
        );
    }
}
