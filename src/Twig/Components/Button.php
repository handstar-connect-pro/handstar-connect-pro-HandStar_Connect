<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Button
{
    use DefaultActionTrait;

    public string $type = 'button';
    public string $class = 'btn btn-primary';
    public string $text = 'Primary';
    public ?string $icon = null;
    public bool $disabled = false;
}
