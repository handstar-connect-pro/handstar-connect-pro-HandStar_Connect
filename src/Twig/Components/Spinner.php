<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Spinner
{
    use DefaultActionTrait;

    public string $type = 'border'; // 'border' or 'grow'
    public string $color = 'text-primary';
    public string $size = ''; // 'sm' for small
    public string $label = 'Loading...';
    public bool $showLabel = true;
}
