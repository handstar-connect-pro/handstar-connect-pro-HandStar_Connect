<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Enums\ContactFonction;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use App\Enums\UserProfil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClubCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Club::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('clubNumber', 'Numéro de club'),
            TextField::new('logo', 'Logo')->hideOnIndex(),
            TextField::new('contactFirstName', 'Prénom du contact'),
            TextField::new('contactLastName', 'Nom du contact'),
            ChoiceField::new('contactPosition', 'Fonction du contact')
                ->setChoices(\array_combine(
                    \array_map(fn (ContactFonction $fn) => $fn->value, ContactFonction::cases()),
                    ContactFonction::cases()
                ))
                ->renderAsNativeWidget(),
            TextField::new('phone', 'Téléphone'),
            TextField::new('address', 'Adresse')->hideOnIndex(),
            TextField::new('postalCode', 'Code postal'),
            TextField::new('city', 'Ville'),
            ChoiceField::new('region', 'Région')
                ->setChoices(\array_combine(
                    \array_map(fn (ListRegion $region) => $region->value, ListRegion::cases()),
                    ListRegion::cases()
                ))
                ->renderAsNativeWidget(),
            ChoiceField::new('division', 'Division')
                ->setChoices(\array_combine(
                    \array_map(fn (LevelDivision $div) => $div->value, LevelDivision::cases()),
                    LevelDivision::cases()
                ))
                ->renderAsNativeWidget(),
            ChoiceField::new('profil', 'Profil')
                ->setChoices(\array_combine(
                    \array_map(fn (UserProfil $profil) => $profil->getLabel(), UserProfil::cases()),
                    UserProfil::cases()
                ))
                ->renderAsNativeWidget(),
            AssociationField::new('user', 'Utilisateur associé'),
        ];
    }
}
