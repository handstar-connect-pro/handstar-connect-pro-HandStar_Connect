<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour sauvegarder une annonce dans les favoris.
 */
class SaveToFavoritesDto
{
    #[Assert\Length(
        max: 500,
        maxMessage: 'Les notes ne peuvent pas dépasser {{ limit }} caractères'
    )]
    public ?string $notes = null;

    /**
     * Convertit le DTO en tableau pour faciliter l'utilisation.
     */
    public function toArray(): array
    {
        return [
            'notes' => $this->notes,
        ];
    }

    /**
     * Crée un DTO à partir d'un tableau.
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        if (isset($data['notes'])) {
            $dto->notes = (string) $data['notes'];
        }

        return $dto;
    }
}
