<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class DashboardHeader
{
    use DefaultActionTrait;

    public string $title = '';
    public string $subtitle = '';
    public array $breadcrumbs = [];
    public array $actions = [];
    public bool $showSearch = false;
    public string $searchPlaceholder = 'Rechercher...';
    public bool $showNotifications = true;
    public int $notificationCount = 0;
    public string $userName = '';
    public string $userAvatar = '';
    public bool $showUserMenu = true;
}
