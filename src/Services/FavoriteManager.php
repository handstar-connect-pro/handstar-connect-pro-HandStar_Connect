<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Announcement;
use App\Entity\SavedAnnouncement;
use App\Entity\User;
use App\Exception\AnnouncementException;
use App\Exception\AlreadyInFavoritesException;
use App\Exception\NotInFavoritesException;
use App\Repository\SavedAnnouncementRepository;
use App\Services\Interface\FavoriteManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteManager implements FavoriteManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SavedAnnouncementRepository $savedAnnouncementRepo
    ) {
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
        try {
            // Vérifier si l'annonce est déjà dans les favoris
            if ($this->isAnnouncementSavedByUser($announcement, $user)) {
                throw new AlreadyInFavoritesException();
            }

            $savedAnnouncement = new SavedAnnouncement();
            $savedAnnouncement->setAnnouncement($announcement);
            $savedAnnouncement->setUser($user);
            $savedAnnouncement->setNotes($notes);

            $this->em->persist($savedAnnouncement);
            $this->em->flush();
        } catch (AlreadyInFavoritesException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de l\'ajout aux favoris: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Retire une annonce des favoris d'un utilisateur.
     *
     * @throws AnnouncementException Si l'annonce n'est pas dans les favoris
     */
    public function removeAnnouncementFromFavorites(Announcement $announcement, User $user): void
    {
        try {
            $savedAnnouncement = $this->savedAnnouncementRepo->findOneBy([
                'announcement' => $announcement,
                'user' => $user,
            ]);

            if (!$savedAnnouncement) {
                throw new NotInFavoritesException();
            }

            $this->em->remove($savedAnnouncement);
            $this->em->flush();
        } catch (NotInFavoritesException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la suppression des favoris: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Récupère les annonces sauvegardées par un utilisateur.
     *
     * @return Announcement[]
     */
    public function getSavedAnnouncements(User $user): array
    {
        $savedAnnouncements = $this->savedAnnouncementRepo->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return array_map(
            fn($saved) => $saved->getAnnouncement(),
            $savedAnnouncements
        );
    }

    /**
     * Vérifie si une annonce est dans les favoris d'un utilisateur.
     */
    public function isAnnouncementSavedByUser(Announcement $announcement, User $user): bool
    {
        $savedAnnouncement = $this->savedAnnouncementRepo->findOneBy([
            'announcement' => $announcement,
            'user' => $user,
        ]);

        return $savedAnnouncement !== null;
    }

    /**
     * Compte le nombre d'annonces sauvegardées par un utilisateur.
     */
    public function countSavedAnnouncements(User $user): int
    {
        return $this->savedAnnouncementRepo->count(['user' => $user]);
    }

    /**
     * Récupère les notes associées à une annonce favorite.
     */
    public function getFavoriteNotes(Announcement $announcement, User $user): ?string
    {
        $savedAnnouncement = $this->savedAnnouncementRepo->findOneBy([
            'announcement' => $announcement,
            'user' => $user,
        ]);

        return $savedAnnouncement?->getNotes();
    }

    /**
     * Met à jour les notes d'une annonce favorite.
     */
    public function updateFavoriteNotes(Announcement $announcement, User $user, string $notes): void
    {
        try {
            $savedAnnouncement = $this->savedAnnouncementRepo->findOneBy([
                'announcement' => $announcement,
                'user' => $user,
            ]);

            if (!$savedAnnouncement) {
                throw new NotInFavoritesException();
            }

            $savedAnnouncement->setNotes($notes);
            $this->em->flush();
        } catch (NotInFavoritesException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la mise à jour des notes: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Récupère les informations détaillées des favoris d'un utilisateur.
     *
     * @return SavedAnnouncement[]
     */
    public function getSavedAnnouncementsDetails(User $user): array
    {
        return $this->savedAnnouncementRepo->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère une entrée favorite spécifique.
     */
    public function getFavoriteEntry(Announcement $announcement, User $user): ?SavedAnnouncement
    {
        return $this->savedAnnouncementRepo->findOneBy([
            'announcement' => $announcement,
            'user' => $user,
        ]);
    }

    /**
     * Supprime toutes les annonces favorites d'un utilisateur.
     *
     * @throws AnnouncementException Si la suppression échoue
     */
    public function clearAllFavorites(User $user): int
    {
        try {
            $savedAnnouncements = $this->savedAnnouncementRepo->findBy(['user' => $user]);
            $count = count($savedAnnouncements);

            foreach ($savedAnnouncements as $savedAnnouncement) {
                $this->em->remove($savedAnnouncement);
            }

            $this->em->flush();

            return $count;
        } catch (\Exception $e) {
            throw new AnnouncementException(
                'Erreur lors de la suppression des favoris: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Vérifie si un utilisateur a des annonces favorites.
     */
    public function hasFavorites(User $user): bool
    {
        return $this->countSavedAnnouncements($user) > 0;
    }
}
