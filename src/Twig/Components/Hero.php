<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Hero
{
    use DefaultActionTrait;

    public string $title = 'Bienvenue sur HandStar Connect';
    public string $subtitle = 'La plateforme de gestion connectée pour le handball français';
    public string $primaryButtonText = 'Commencer gratuitement';
    public string $primaryButtonLink = '/register';
    public string $secondaryButtonText = 'En savoir plus';
    public string $secondaryButtonLink = '#features';
    public string $background = 'bg-light';
    public bool $showImage = true;
    public string $imagePath = 'images/hero-image.png';
}
