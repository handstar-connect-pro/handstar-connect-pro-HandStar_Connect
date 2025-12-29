<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Modal
{
    use DefaultActionTrait;

    public string $id = 'exampleModal';
    public string $title = 'New message';
    public string $size = ''; // empty, modal-sm, modal-lg, modal-xl
    public bool $staticBackdrop = false;
    public bool $scrollable = false;
    public bool $centered = false;

    /** @var array<array{text: string, target: string, whatever: string}> */
    public array $triggers = [
        ['text' => 'Open modal for @mdo', 'target' => '#exampleModal', 'whatever' => '@mdo'],
        ['text' => 'Open modal for @fat', 'target' => '#exampleModal', 'whatever' => '@fat'],
        ['text' => 'Open modal for @getbootstrap', 'target' => '#exampleModal', 'whatever' => '@getbootstrap'],
    ];

    public string $recipientLabel = 'Recipient:';
    public string $messageLabel = 'Message:';
    public string $closeButtonText = 'Close';
    public string $submitButtonText = 'Send message';
    public bool $includeForm = true;
    public string $customBody = ''; // Alternative to form
}
