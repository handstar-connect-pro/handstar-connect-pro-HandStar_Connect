<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Sidebar
{
    use DefaultActionTrait;

    public array $menuItems = [];
    public string $brand = 'HandStar Connect';
    public string $brandHref = '/user/dashboard';
    public string $variant = 'light'; // 'light' or 'dark'
    public bool $collapsible = true;
    public bool $collapsed = false;
    public string $width = '250px';
    public string $logoPath = '/images/logo.png';
    public bool $showLogo = true;
    public string $userName = '';
    public string $userRole = '';
    public string $userAvatar = '';
}
