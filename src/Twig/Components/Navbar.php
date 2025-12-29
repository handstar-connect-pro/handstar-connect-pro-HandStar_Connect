<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Navbar
{
    use DefaultActionTrait;

    public string $variant = 'navbar'; // 'navbar' or 'nav'
    public string $brand = 'Navbar';
    public string $brandType = 'link'; // 'link' or 'heading'
    public string $brandHref = '#';
    public string $background = 'bg-body-tertiary';
    public string $expand = 'lg'; // 'sm', 'md', 'lg', 'xl', 'xxl', or '' for always collapsed
    public string $alignment = ''; // 'center', 'end', etc. for justify-content-*
    public string $containerClass = 'container-fluid';
    public array $items = []; // Optional: array of menu items
    public bool $showToggler = true;
    public string $collapseId = 'navbarNav';
    public bool $showSearch = false;
    public string $searchPlaceholder = 'Search';
}
