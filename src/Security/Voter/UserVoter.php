<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const VIEW = 'USER_VIEW';
    public const EDIT = 'USER_EDIT';
    public const DELETE = 'USER_DELETE';

    public function __construct(
        private readonly Security $security
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Ce voter ne s'applique qu'aux objets User
        if (!$subject instanceof User) {
            return false;
        }

        // Vérifier que l'attribut est supporté
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // L'utilisateur doit être connecté
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var User $targetUser */
        $targetUser = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($targetUser, $user);
            case self::EDIT:
                return $this->canEdit($targetUser, $user);
            case self::DELETE:
                return $this->canDelete($targetUser, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $targetUser, UserInterface $currentUser): bool
    {
        // Les administrateurs peuvent voir tous les utilisateurs
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Les utilisateurs peuvent se voir eux-mêmes
        return $targetUser->getUserIdentifier() === $currentUser->getUserIdentifier();
    }

    private function canEdit(User $targetUser, UserInterface $currentUser): bool
    {
        // Les administrateurs peuvent modifier tous les utilisateurs
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Les utilisateurs peuvent se modifier eux-mêmes
        return $targetUser->getUserIdentifier() === $currentUser->getUserIdentifier();
    }

    private function canDelete(User $targetUser, UserInterface $currentUser): bool
    {
        // Seuls les administrateurs peuvent supprimer des utilisateurs
        // (un utilisateur ne peut pas se supprimer lui-même)
        return $this->security->isGranted('ROLE_ADMIN')
            && $targetUser->getUserIdentifier() !== $currentUser->getUserIdentifier();
    }
}
