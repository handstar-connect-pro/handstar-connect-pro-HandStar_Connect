<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Features
{
    use DefaultActionTrait;

    public array $features = [
        [
            'icon' => 'bi-people-fill',
            'title' => 'Gestion des équipes',
            'description' => 'Organisez vos joueurs, entraîneurs et staff technique avec des outils dédiés.',
            'color' => 'primary'
        ],
        [
            'icon' => 'bi-calendar-week-fill',
            'title' => 'Planning intelligent',
            'description' => 'Planifiez entraînements, matchs et événements avec notifications automatiques.',
            'color' => 'success'
        ],
        [
            'icon' => 'bi-graph-up-arrow',
            'title' => 'Analyses avancées',
            'description' => 'Suivez les performances avec des tableaux de bord et rapports détaillés.',
            'color' => 'info'
        ],
        [
            'icon' => 'bi-chat-dots-fill',
            'title' => 'Communication intégrée',
            'description' => 'Messagerie instantanée et annonces pour toute l\'organisation.',
            'color' => 'warning'
        ],
        [
            'icon' => 'bi-file-earmark-medical-fill',
            'title' => 'Suivi médical',
            'description' => 'Gestion des blessures, certificats et suivis santé des joueurs.',
            'color' => 'danger'
        ],
        [
            'icon' => 'bi-phone-fill',
            'title' => 'Application mobile',
            'description' => 'Accédez à toutes les fonctionnalités depuis votre smartphone.',
            'color' => 'secondary'
        ],
    ];
}
