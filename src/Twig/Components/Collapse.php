<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Collapse
{
    use DefaultActionTrait;

    public string $id = 'collapseExample';
    public string $triggerText = 'Toggle collapse';
    public string $triggerType = 'button'; // 'button' or 'link'
    public string $triggerClass = 'btn btn-primary';
    public string $content = 'Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.';
    public string $cardClass = 'card card-body';
    public bool $showMultipleTriggers = false;
    public string $additionalTriggerText = 'Button with data-bs-target';
    public string $additionalTriggerType = 'button';
}
