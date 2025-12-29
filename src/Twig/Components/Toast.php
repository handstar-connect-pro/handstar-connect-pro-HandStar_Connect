<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Toast
{
    use DefaultActionTrait;

    public string $title = 'Bootstrap';
    public string $message = 'Hello, world! This is a toast message.';
    public string $imageSrc = '...';
    public string $imageAlt = '...';
    public string $time = '11 mins ago';
    public bool $autohide = true;
    public int $delay = 5000; // milliseconds
    public string $position = ''; // toast-container position classes
    public string $background = ''; // bg-primary, bg-success, etc.
    public string $textColor = ''; // text-white, etc.
}
