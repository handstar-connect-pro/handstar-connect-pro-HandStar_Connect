<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Carousel
{
    use DefaultActionTrait;

    /** @var array<array{src: string, alt: string, title?: string, description?: string}> */
    public array $slides = [
        ['src' => '...', 'alt' => '...', 'title' => 'Slide 1', 'description' => 'Description for slide 1'],
        ['src' => '...', 'alt' => '...', 'title' => 'Slide 2', 'description' => 'Description for slide 2'],
        ['src' => '...', 'alt' => '...', 'title' => 'Slide 3', 'description' => 'Description for slide 3'],
    ];

    public string $id = 'carouselExampleRide';
    public bool $showControls = true;
    public bool $showIndicators = false;
    public bool $autoPlay = true;
    public string $interval = '5000';
}
