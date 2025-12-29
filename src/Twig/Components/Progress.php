<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Progress
{
    use DefaultActionTrait;

    public int $value = 0;
    public int $min = 0;
    public int $max = 100;
    public string $label = 'Basic example';
    public string $style = 'bg-primary'; // Bootstrap background class
    public bool $striped = false;
    public bool $animated = false;
    public string $height = ''; // e.g. 'progress-bar-sm' or custom height
    public bool $showLabel = false; // Show percentage label inside bar
}
