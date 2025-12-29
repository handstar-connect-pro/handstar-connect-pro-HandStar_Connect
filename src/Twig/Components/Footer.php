<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Footer
{
    use DefaultActionTrait;

    public string $copyright;
    public array $links = [
        ['text' => 'Mentions légales', 'href' => '#'],
        ['text' => 'Politique de confidentialité', 'href' => '#'],
        ['text' => 'Conditions d\'utilisation', 'href' => '#'],
        ['text' => 'Contact', 'href' => '#'],
    ];
    public array $socialLinks = [
        ['icon' => 'bi-facebook', 'href' => '#', 'label' => 'Facebook'],
        ['icon' => 'bi-twitter-x', 'href' => '#', 'label' => 'Twitter'],
        ['icon' => 'bi-linkedin', 'href' => '#', 'label' => 'LinkedIn'],
        ['icon' => 'bi-instagram', 'href' => '#', 'label' => 'Instagram'],
    ];

    public function __construct()
    {
        $this->copyright = '© ' . \date('Y') . ' HandStar Connect. Tous droits réservés.';
    }
}
