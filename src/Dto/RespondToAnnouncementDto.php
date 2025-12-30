<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour répondre à une annonce.
 */
class RespondToAnnouncementDto
{
    #[Assert\NotBlank(message: 'Le message de réponse est requis')]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: 'Le message doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le message ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $message = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'Le chemin du fichier ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $attachmentPath = null;

    /**
     * Convertit le DTO en tableau pour faciliter l'utilisation.
     */
    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'attachmentPath' => $this->attachmentPath,
        ];
    }

    /**
     * Crée un DTO à partir d'un tableau.
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        if (isset($data['message'])) {
            $dto->message = (string) $data['message'];
        }

        if (isset($data['attachmentPath'])) {
            $dto->attachmentPath = (string) $data['attachmentPath'];
        }

        return $dto;
    }
}
