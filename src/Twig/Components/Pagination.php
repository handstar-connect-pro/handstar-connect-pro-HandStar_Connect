<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Pagination
{
    use DefaultActionTrait;

    public int $currentPage = 1;
    public int $totalPages = 3;
    public string $alignment = 'center'; // 'start', 'center', 'end'
    public bool $showPreviousNext = true;
    public string $previousText = 'Previous';
    public string $nextText = 'Next';
    public bool $previousDisabled = false;
    public bool $nextDisabled = false;
    public string $ariaLabel = 'Page navigation example';
    public string $size = ''; // 'sm', 'lg' for pagination-sm, pagination-lg
}
