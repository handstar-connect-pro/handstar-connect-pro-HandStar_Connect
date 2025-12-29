<?php

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserDashboardController extends AbstractController
{
    #[Route('/user/dashboard', name: 'user_dashboard', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        return $this->render('user/user_dashboard.html.twig', [
            'user' => $user,
        ]);
    }
}
