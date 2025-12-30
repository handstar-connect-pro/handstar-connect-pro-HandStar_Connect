<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Listener Doctrine pour gérer automatiquement les timestamps (createdAt, updatedAt).
 */
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: User::class)]
class TimestampListener
{
    /**
     * Définit les dates de création et de mise à jour avant la persistance.
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $now = new \DateTimeImmutable();

            if ($entity->getCreatedAt() === null) {
                $entity->setCreatedAt($now);
            }

            if ($entity->getUpdatedAt() === null) {
                $entity->setUpdatedAt($now);
            }
        }
    }

    /**
     * Met à jour la date de modification avant la mise à jour.
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
