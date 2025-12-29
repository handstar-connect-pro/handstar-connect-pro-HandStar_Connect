<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListUsersControllerTest extends WebTestCase
{
    public function testListUsersAsAdmin(): void
    {
        $client = static::createClient();

        // Créer un utilisateur admin
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword('password');
        $admin->setRoles(['ROLE_ADMIN']);
        $userRepository->save($admin, true);

        // Simuler la connexion
        $client->loginUser($admin);

        // Faire la requête
        $client->request('GET', '/users');

        // Vérifier la réponse
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testListUsersAsNonAdmin(): void
    {
        $client = static::createClient();

        // Créer un utilisateur normal
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $userRepository->save($user, true);

        // Simuler la connexion
        $client->loginUser($user);

        // Faire la requête - devrait être refusé
        $client->request('GET', '/users');

        // Vérifier l'accès refusé
        $this->assertResponseStatusCodeSame(403);
    }

    public function testListUsersAsAnonymous(): void
    {
        $client = static::createClient();

        // Faire la requête sans être connecté
        $client->request('GET', '/users');

        // Devrait rediriger vers la page de login
        $this->assertResponseRedirects('/login');
    }
}
