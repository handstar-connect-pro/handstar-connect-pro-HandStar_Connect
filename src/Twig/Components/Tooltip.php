<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Tooltip
{
    use DefaultActionTrait;

    public string $text = 'Custom tooltip';
    public string $tooltip = 'This top tooltip is themed via CSS variables.';
    public string $placement = 'top'; // top, bottom, left, right
    public string $customClass = 'custom-tooltip';
    public string $buttonClass = 'btn btn-secondary';
    public bool $enabled = true;
}
