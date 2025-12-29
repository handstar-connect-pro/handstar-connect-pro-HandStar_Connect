<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class HomeNavbar
{
    use DefaultActionTrait;

    public array $menuItems = [
        ['text' => 'Accueil', 'href' => '#', 'active' => true],
        ['text' => 'Fonctionnalités', 'href' => '#features'],
        ['text' => 'À propos', 'href' => '#about'],
    ];

    public string $logoPath = 'images/HandStar_Connect.png';
    public string $registerPath = '/register';
    public string $loginPath = '/login';
    public string $background = 'bg-white';
    public bool $fixed = true;
}
