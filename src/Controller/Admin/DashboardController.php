<?php

namespace App\Controller\Admin;

use App\Entity\Announcement;
use App\Entity\AnnouncementResponse;
use App\Entity\Club;
use App\Entity\Coach;
use App\Entity\MentalTrainer;
use App\Entity\PhysicalTrainer;
use App\Entity\PhysioTherapist;
use App\Entity\Player;
use App\Entity\Referee;
use App\Entity\SavedAnnouncement;
use App\Entity\TechnicalDirector;
use App\Entity\User;
use App\Entity\VideoAnalyst;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Option 3: Render custom template using EasyAdmin's default layout
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HandStar Connect');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class)->setController(UserCrudController::class);
        yield MenuItem::section('Annonces');
        yield MenuItem::linkToCrud('Annonces', 'fa fa-bullhorn', Announcement::class);
        yield MenuItem::linkToCrud('Réponses aux annonces', 'fa fa-reply', AnnouncementResponse::class);
        yield MenuItem::linkToCrud('Annonces sauvegardées', 'fa fa-bookmark', SavedAnnouncement::class);
        yield MenuItem::section('Clubs');
        yield MenuItem::linkToCrud('Clubs (Entités)', 'fa fa-flag', Club::class);
        yield MenuItem::linkToCrud('Utilisateurs Clubs', 'fa fa-users', User::class)->setController(ClubUserCrudController::class);
        yield MenuItem::section('Personnel');
        yield MenuItem::linkToCrud('Joueurs', 'fa fa-running', User::class)->setController(PlayerUserCrudController::class);
        yield MenuItem::linkToCrud('Entraîneurs', 'fa fa-user-tie', User::class)->setController(CoachUserCrudController::class);
        yield MenuItem::linkToCrud('Directeurs Techniques', 'fa fa-id-badge', User::class)->setController(TechnicalDirectorUserCrudController::class);
        yield MenuItem::linkToCrud('Préparateurs Physiques', 'fa fa-dumbbell', User::class)->setController(PhysicalTrainerUserCrudController::class);
        yield MenuItem::linkToCrud('Préparateurs Mentaux', 'fa fa-brain', User::class)->setController(MentalTrainerUserCrudController::class);
        yield MenuItem::linkToCrud('Kinésithérapeutes', 'fa fa-heartbeat', User::class)->setController(PhysiotherapistUserCrudController::class);
        yield MenuItem::linkToCrud('Analystes Vidéo', 'fa fa-video', User::class)->setController(VideoAnalystUserCrudController::class);
        yield MenuItem::linkToCrud('Arbitres', 'fa fa-whistle', User::class)->setController(RefereeUserCrudController::class);
        yield MenuItem::linkToCrud('Entraîneurs de Gardiens', 'fa fa-hockey-puck', User::class)->setController(GoalkeeperCoachUserCrudController::class);
    }
}
