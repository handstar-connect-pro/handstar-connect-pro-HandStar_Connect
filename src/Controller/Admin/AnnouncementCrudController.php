<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Announcement;
use App\Enums\AnnouncementStatus;
use App\Enums\AnnouncementType;
use App\Enums\LevelDivision;
use App\Enums\ListRegion;
use App\Enums\UserProfil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnnouncementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Announcement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('offerType', 'Type d\'offre')
                ->setChoices(\array_combine(
                    \array_map(fn (AnnouncementType $type) => $type->value, AnnouncementType::cases()),
                    AnnouncementType::cases()
                ))
                ->renderAsNativeWidget(),
            TextField::new('offerTitle', 'Titre de l\'offre'),
            TextareaField::new('offerDescription', 'Description de l\'offre'),
            ChoiceField::new('offerUserProfil', 'Profil utilisateur cible')
                ->setChoices(\array_combine(
                    \array_map(fn (UserProfil $profil) => $profil->getLabel(), UserProfil::cases()),
                    UserProfil::cases()
                ))
                ->renderAsNativeWidget(),
            TextField::new('positionSought', 'Poste recherché'),
            ChoiceField::new('leagueConcerned', 'Division concernée')
                ->setChoices(\array_combine(
                    \array_map(fn (LevelDivision $div) => $div->value, LevelDivision::cases()),
                    LevelDivision::cases()
                ))
                ->renderAsNativeWidget(),
            ChoiceField::new('location', 'Région')
                ->setChoices(\array_combine(
                    \array_map(fn (ListRegion $region) => $region->value, ListRegion::cases()),
                    ListRegion::cases()
                ))
                ->renderAsNativeWidget(),
            ChoiceField::new('offerStatus', 'Statut de l\'offre')
                ->setChoices(\array_combine(
                    \array_map(fn (AnnouncementStatus $status) => $status->value, AnnouncementStatus::cases()),
                    AnnouncementStatus::cases()
                ))
                ->renderAsNativeWidget(),
            IntegerField::new('viewCount', 'Nombre de vues')->hideOnForm(),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnForm(),
            ChoiceField::new('profil', 'Profil')
                ->setChoices(\array_combine(
                    \array_map(fn (UserProfil $profil) => $profil->getLabel(), UserProfil::cases()),
                    UserProfil::cases()
                ))
                ->renderAsNativeWidget(),
            DateTimeField::new('expiresAt', 'Expire le'),
            AssociationField::new('responses', 'Réponses')->hideOnForm(),
            AssociationField::new('savedAnnouncements', 'Sauvegardes')->hideOnForm(),
        ];
    }
}
