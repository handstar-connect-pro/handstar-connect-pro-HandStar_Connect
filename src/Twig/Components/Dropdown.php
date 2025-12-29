<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Dropdown
{
    use DefaultActionTrait;

    public string $buttonText = 'Action';
    public string $buttonType = 'primary'; // primary, secondary, success, danger, warning, info, light, dark
    public bool $splitButton = false;
    public string $dropdownToggleText = 'Toggle Dropdown';
    public string $alignment = 'dropdown'; // dropdown, dropup, dropstart, dropend
    public string $menuAlignment = ''; // dropdown-menu-end for right alignment

    /** @var array<array{type: string, content: string, href?: string, divider?: bool}> */
    public array $items = [
        ['type' => 'link', 'content' => 'Action', 'href' => '#'],
        ['type' => 'link', 'content' => 'Another action', 'href' => '#'],
        ['type' => 'link', 'content' => 'Something else here', 'href' => '#'],
        ['type' => 'divider', 'divider' => true],
        ['type' => 'link', 'content' => 'Separated link', 'href' => '#'],
    ];
}
