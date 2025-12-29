<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CreateUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator
    ) {}

    /**
     * Crée un nouvel utilisateur avec validation via DTO
     *
     * @throws \InvalidArgumentException Si la validation échoue
     */
    public function createUserFromDto(CreateUserDto $dto): User
    {
        // Valider le DTO
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        // Vérifier si l'email est déjà utilisé
        if ($this->isEmailTaken($dto->email)) {
            throw new \InvalidArgumentException('Cet email est déjà utilisé');
        }

        $user = new User();
        $user->setEmail($dto->email);

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);

        $user->setRoles([$dto->role]);

        $this->userRepository->save($user, true);

        return $user;
    }

    /**
     * Crée un nouvel utilisateur (méthode legacy)
     */
    public function createUser(string $email, string $plainPassword, array $roles = ['ROLE_USER']): User
    {
        $user = new User();
        $user->setEmail($email);

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $user->setRoles($roles);

        $this->userRepository->save($user, true);

        return $user;
    }

    /**
     * Trouve un utilisateur par email
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * Vérifie si un email est déjà utilisé
     */
    public function isEmailTaken(string $email): bool
    {
        return $this->findUserByEmail($email) !== null;
    }

    /**
     * Met à jour le mot de passe d'un utilisateur
     */
    public function updatePassword(User $user, string $newPlainPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPlainPassword);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);
    }
}
