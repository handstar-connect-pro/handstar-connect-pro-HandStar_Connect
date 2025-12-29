<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Card
{
    use DefaultActionTrait;

    public string $title = 'Card title';
    public string $text = 'Some quick example text to build on the card title and make up the bulk of the card’s content.';
    public string $imageSrc = '...';
    public string $imageAlt = '...';
    public string $buttonText = 'Go somewhere';
    public string $buttonClass = 'btn btn-primary';
    public string $buttonHref = '#';
    public string $widthClass = 'w-100'; // Bootstrap width utility
    public bool $loading = false;
    public bool $showImage = true;
    public bool $showButton = true;
    public string $ariaHidden = 'false';
}
