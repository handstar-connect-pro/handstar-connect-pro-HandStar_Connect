<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'L\'email est obligatoire')]
        #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
        #[Assert\Length(max: 180, maxMessage: 'L\'email ne peut pas dépasser {{ limit }} caractères')]
        public string $email,

        #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
        #[Assert\Length(
            min: 8,
            max: 255,
            minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères',
            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères'
        )]
        #[Assert\Regex(
            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            message: 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial'
        )]
        public string $password,

        #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
            maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'
        )]
        public string $firstName,

        #[Assert\NotBlank(message: 'Le nom est obligatoire')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
            maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
        )]
        public string $lastName,

        #[Assert\Choice(
            choices: ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
            message: 'Le rôle {{ value }} n\'est pas valide'
        )]
        public string $role = 'ROLE_USER'
    ) {}

    /**
     * Retourne le nom complet de l'utilisateur
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
