<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Announcement;
use App\Entity\User;
use App\Enums\AnnouncementStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Announcement>
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    /**
     * Trouve les annonces visibles pour un utilisateur
     * Basé sur les règles d'accès définies dans AccessControlService.
     */
    public function findVisibleForUser(User $user, array $filters = []): array
    {
        $userProfil = $user->getProfil();

        if ($userProfil === null) {
            return []; // Utilisateur sans profil ne peut voir aucune annonce
        }

        $qb = $this->createQueryBuilder('a')
            ->where('a.offerStatus = :status')
            ->andWhere('a.expiresAt > :now')
            ->setParameter('status', AnnouncementStatus::ACTIVE)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('a.createdAt', 'DESC');

        // Filtre par type de profil si spécifié
        if (isset($filters['profil'])) {
            $qb->andWhere('a.offerUserProfil = :profil')
               ->setParameter('profil', $filters['profil']);
        }

        // Filtre par région si spécifié
        if (isset($filters['region'])) {
            $qb->andWhere('a.location = :region')
               ->setParameter('region', $filters['region']);
        }

        // Filtre par division si spécifié
        if (isset($filters['division'])) {
            $qb->andWhere('a.leagueConcerned = :division')
               ->setParameter('division', $filters['division']);
        }

        // Note: Le filtrage par règles d'accès se fait au niveau applicatif
        // pour des raisons de performance et de simplicité
        // On pourrait optimiser avec une sous-requête si nécessaire
        $allAnnouncements = $qb->getQuery()->getResult();

        // Filtrer selon les règles d'accès
        return \array_filter($allAnnouncements, function ($announcement) {
            $announcementProfil = $announcement->getOfferUserProfil();
            if ($announcementProfil === null) {
                return false;
            }

            // Utiliser AccessControlService pour vérifier la visibilité
            // Note: Pour l'instant, on retourne true - à implémenter avec injection
            // du service dans une version ultérieure
            return true; // Temporaire - à remplacer par la logique réelle
        });
    }

    /**
     * Trouve les annonces actives (non expirées et non fermées).
     */
    public function findActiveAnnouncements(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.offerStatus = :status')
            ->andWhere('a.expiresAt > :now')
            ->setParameter('status', AnnouncementStatus::ACTIVE)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les annonces créées par un utilisateur spécifique
     * (à adapter selon la relation réelle entre User et Announcement).
     */
    public function findByUser(User $user): array
    {
        // Note: Cette méthode nécessite que Announcement ait une relation avec User
        // Pour l'instant, on retourne un tableau vide
        // À implémenter quand la relation sera définie
        return [];
    }

    //    /**
    //     * @return Announcement[] Returns an array of Announcement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Announcement
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
