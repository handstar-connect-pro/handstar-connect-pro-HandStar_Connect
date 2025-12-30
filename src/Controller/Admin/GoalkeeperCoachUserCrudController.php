<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enums\UserProfil;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GoalkeeperCoachUserCrudController extends BaseFilteredCrudController
{
    protected function getProfilFilter(): UserProfil
    {
        return UserProfil::GOALKEEPER_COACH;
    }

    protected function getPageTitle(): string
    {
        return 'Entraîneurs de Gardiens';
    }

    protected function getEntityLabelPlural(): string
    {
        return 'Entraîneurs de Gardiens';
    }

    protected function getEntityLabelSingular(): string
    {
        return 'Entraîneur de Gardiens';
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            EmailField::new('email', 'Email'),
            ChoiceField::new('profil', 'Profil')
                ->setChoices([
                    UserProfil::GOALKEEPER_COACH->getLabel() => UserProfil::GOALKEEPER_COACH
                ])
                ->renderAsNativeWidget(),
            ArrayField::new('roles', 'Rôles'),
            BooleanField::new('isVerified', 'Vérifié'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnForm(),
        ];
    }
}
