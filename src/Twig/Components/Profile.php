<?php

namespace App\Twig\Components;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Profile
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?User $user = null;

    public function __construct(private Security $security)
    {
        $this->user = $security->getUser();
    }
}
