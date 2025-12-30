<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\UserProfil;
use App\Services\Interface\AccessControlServiceInterface;

class AccessControlService implements AccessControlServiceInterface
{
    /**
     * Matrice de qui peut répondre à qui
     * Basée sur les règles fournies, avec correction de l'asymétrie des entraîneurs.
     */
    private static function getResponseMatrix(): array
    {
        static $matrix = null;

        if ($matrix === null) {
            $allProfiles = \array_map(fn ($p) => $p->value, UserProfil::cases());

            $matrix = [
                // Joueurs
                UserProfil::PLAYER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Clubs (peuvent répondre à tous)
                UserProfil::CLUB->value => $allProfiles,

                // Entraîneurs (peuvent répondre à tous - CORRIGÉ)
                UserProfil::COACH->value => $allProfiles,

                // Entraîneurs des gardiens (mêmes règles que les entraîneurs)
                UserProfil::GOALKEEPER_COACH->value => $allProfiles,

                // Préparateurs Physiques
                UserProfil::PHYSICAL_TRAINER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Préparateurs Mentaux
                UserProfil::MENTAL_TRAINER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Analystes Vidéos
                UserProfil::VIDEO_ANALYST->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Kinésithérapeutes
                UserProfil::PHYSIOTHERAPIST->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Arbitres
                UserProfil::REFEREE->value => [
                    UserProfil::CLUB->value,
                ],

                // Directeurs Techniques
                UserProfil::TECHNICAL_DIRECTOR->value => [
                    UserProfil::CLUB->value,
                ],
            ];
        }

        return $matrix;
    }

    /**
     * Matrice de qui peut voir les annonces de qui
     * Basée sur les règles fournies, avec correction de l'asymétrie des entraîneurs.
     */
    private static function getVisibilityMatrix(): array
    {
        static $matrix = null;

        if ($matrix === null) {
            $allProfiles = \array_map(fn ($p) => $p->value, UserProfil::cases());

            $matrix = [
                // Annonces des Joueurs
                UserProfil::PLAYER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Annonces des Clubs (visibles par tous)
                UserProfil::CLUB->value => $allProfiles,

                // Annonces des Entraîneurs (CORRIGÉ : ajout des Joueurs)
                UserProfil::COACH->value => [
                    UserProfil::CLUB->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                    UserProfil::PLAYER->value, // CORRECTION : ajout des joueurs
                ],

                // Annonces des Entraîneurs des gardiens (mêmes règles que les entraîneurs)
                UserProfil::GOALKEEPER_COACH->value => [
                    UserProfil::CLUB->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                    UserProfil::PLAYER->value, // CORRECTION : ajout des joueurs
                ],

                // Annonces des Préparateurs Physiques
                UserProfil::PHYSICAL_TRAINER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Annonces des Préparateurs Mentaux
                UserProfil::MENTAL_TRAINER->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Annonces des Analystes Vidéos
                UserProfil::VIDEO_ANALYST->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Annonces des Kinésithérapeutes
                UserProfil::PHYSIOTHERAPIST->value => [
                    UserProfil::CLUB->value,
                    UserProfil::COACH->value,
                    UserProfil::GOALKEEPER_COACH->value,
                    UserProfil::TECHNICAL_DIRECTOR->value,
                ],

                // Annonces des Arbitres
                UserProfil::REFEREE->value => [
                    UserProfil::CLUB->value,
                ],

                // Annonces des Directeurs Techniques
                UserProfil::TECHNICAL_DIRECTOR->value => [
                    UserProfil::CLUB->value,
                ],
            ];
        }

        return $matrix;
    }

    /**
     * Vérifie si un profil peut répondre à une annonce d'un autre profil.
     */
    public function canRespond(UserProfil $responder, UserProfil $target): bool
    {
        $allowedTargets = self::getResponseMatrix()[$responder->value] ?? [];

        return \in_array($target->value, $allowedTargets, true);
    }

    /**
     * Vérifie si un profil peut voir les annonces d'un autre profil.
     */
    public function canSeeAnnouncements(UserProfil $viewer, UserProfil $announcementOwner): bool
    {
        $allowedViewers = self::getVisibilityMatrix()[$announcementOwner->value] ?? [];

        return \in_array($viewer->value, $allowedViewers, true);
    }

    /**
     * Récupère tous les profils auxquels un profil peut répondre.
     */
    public function getAllowedResponseTargets(UserProfil $responder): array
    {
        $targetValues = self::getResponseMatrix()[$responder->value] ?? [];

        return \array_map(fn ($value) => UserProfil::from($value), $targetValues);
    }

    /**
     * Récupère tous les profils qui peuvent voir les annonces d'un profil.
     */
    public function getAllowedViewers(UserProfil $announcementOwner): array
    {
        $viewerValues = self::getVisibilityMatrix()[$announcementOwner->value] ?? [];

        return \array_map(fn ($value) => UserProfil::from($value), $viewerValues);
    }

    /**
     * Vérifie si la relation est symétrique (A peut répondre à B ET B peut voir les annonces de A).
     */
    public function isRelationshipSymmetric(UserProfil $profileA, UserProfil $profileB): bool
    {
        $aCanRespondToB = $this->canRespond($profileA, $profileB);
        $bCanSeeA = $this->canSeeAnnouncements($profileB, $profileA);

        return $aCanRespondToB && $bCanSeeA;
    }

    /**
     * Valide la cohérence de toutes les règles
     * Retourne un tableau des incohérences trouvées.
     */
    public function validateRules(): array
    {
        $inconsistencies = [];
        $allProfiles = UserProfil::cases();

        foreach ($allProfiles as $profileA) {
            foreach ($allProfiles as $profileB) {
                $aCanRespondToB = $this->canRespond($profileA, $profileB);
                $bCanSeeA = $this->canSeeAnnouncements($profileB, $profileA);

                // Règle : Si A peut répondre à B, alors B doit pouvoir voir les annonces de A
                if ($aCanRespondToB && !$bCanSeeA) {
                    $inconsistencies[] = \sprintf(
                        '%s peut répondre à %s mais %s ne peut pas voir les annonces de %s',
                        $profileA->getLabel(),
                        $profileB->getLabel(),
                        $profileB->getLabel(),
                        $profileA->getLabel()
                    );
                }
            }
        }

        return $inconsistencies;
    }

    /**
     * Vérifie si un profil a des permissions complètes (peut répondre à tous et être vu par tous).
     */
    public function hasFullPermissions(UserProfil $profile): bool
    {
        $allProfiles = \array_map(fn ($p) => $p->value, UserProfil::cases());

        $canRespondToAll = empty(\array_diff($allProfiles, self::getResponseMatrix()[$profile->value] ?? []));
        $canBeSeenByAll = empty(\array_diff($allProfiles, self::getVisibilityMatrix()[$profile->value] ?? []));

        return $canRespondToAll && $canBeSeenByAll;
    }

    /**
     * Récupère la description des règles pour un profil.
     */
    public function getProfileRulesDescription(UserProfil $profile): array
    {
        $responseTargets = $this->getAllowedResponseTargets($profile);
        $allowedViewers = $this->getAllowedViewers($profile);

        return [
            'profile' => $profile->getLabel(),
            'can_respond_to' => \array_map(fn ($p) => $p->getLabel(), $responseTargets),
            'can_be_seen_by' => \array_map(fn ($p) => $p->getLabel(), $allowedViewers),
            'has_full_permissions' => $this->hasFullPermissions($profile),
        ];
    }

    /**
     * Récupère toutes les règles sous forme de tableau.
     */
    public function getAllRules(): array
    {
        $rules = [];
        foreach (UserProfil::cases() as $profile) {
            $rules[$profile->value] = $this->getProfileRulesDescription($profile);
        }

        return $rules;
    }

    /**
     * Vérifie si un ensemble de profils peut interagir entre eux.
     */
    public function canProfilesInteract(array $profiles): bool
    {
        if (\count($profiles) < 2) {
            return true;
        }

        foreach ($profiles as $profileA) {
            foreach ($profiles as $profileB) {
                if ($profileA === $profileB) {
                    continue;
                }

                $aCanRespondToB = $this->canRespond($profileA, $profileB);
                $bCanRespondToA = $this->canRespond($profileB, $profileA);

                if (!$aCanRespondToB && !$bCanRespondToA) {
                    return false;
                }
            }
        }

        return true;
    }
}
