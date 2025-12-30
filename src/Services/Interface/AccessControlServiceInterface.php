<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Enums\UserProfil;

/**
 * Interface pour le service de contrôle d'accès.
 */
interface AccessControlServiceInterface
{
    /**
     * Vérifie si un profil peut répondre à une annonce d'un autre profil.
     */
    public function canRespond(UserProfil $responder, UserProfil $target): bool;

    /**
     * Vérifie si un profil peut voir les annonces d'un autre profil.
     */
    public function canSeeAnnouncements(UserProfil $viewer, UserProfil $announcementOwner): bool;

    /**
     * Récupère tous les profils auxquels un profil peut répondre.
     *
     * @return UserProfil[]
     */
    public function getAllowedResponseTargets(UserProfil $responder): array;

    /**
     * Récupère tous les profils qui peuvent voir les annonces d'un profil.
     *
     * @return UserProfil[]
     */
    public function getAllowedViewers(UserProfil $announcementOwner): array;

    /**
     * Vérifie si la relation est symétrique (A peut répondre à B ET B peut voir les annonces de A).
     */
    public function isRelationshipSymmetric(UserProfil $profileA, UserProfil $profileB): bool;

    /**
     * Valide la cohérence de toutes les règles.
     *
     * @return string[] Tableau des incohérences trouvées
     */
    public function validateRules(): array;

    /**
     * Vérifie si un profil a des permissions complètes (peut répondre à tous et être vu par tous).
     */
    public function hasFullPermissions(UserProfil $profile): bool;

    /**
     * Récupère la description des règles pour un profil.
     */
    public function getProfileRulesDescription(UserProfil $profile): array;

    /**
     * Récupère toutes les règles sous forme de tableau.
     */
    public function getAllRules(): array;

    /**
     * Vérifie si un ensemble de profils peut interagir entre eux.
     */
    public function canProfilesInteract(array $profiles): bool;
}
