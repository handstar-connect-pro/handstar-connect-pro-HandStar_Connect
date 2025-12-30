<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Enums\UserProfil;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * Contrôleur CRUD de base avec filtrage par profil.
 */
abstract class BaseFilteredCrudController extends AbstractCrudController
{
    abstract public static function getEntityFqcn(): string;

    abstract protected function getProfilFilter(): UserProfil;

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', $this->getPageTitle())
            ->setEntityLabelInPlural($this->getEntityLabelPlural())
            ->setEntityLabelInSingular($this->getEntityLabelSingular());
    }

    public function configureFilters(Filters $filters): Filters
    {
        // Ajouter un filtre pour le profil
        return $filters->add('profil');
    }

    public function configureFields(string $pageName): iterable
    {
        // Par défaut, retourner les champs par défaut
        return parent::configureFields($pageName);
    }

    public function createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Filtrer automatiquement par profil
        // L'alias de l'entité est déterminé par le parent
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder
            ->andWhere($rootAlias . '.profil = :profil')
            ->setParameter('profil', $this->getProfilFilter());

        return $queryBuilder;
    }

    abstract protected function getPageTitle(): string;

    abstract protected function getEntityLabelPlural(): string;

    abstract protected function getEntityLabelSingular(): string;

    /**
     * Crée une instance de l'entité.
     * Cette méthode est requise par AbstractCrudController.
     */
    public function createEntity(string $entityFqcn)
    {
        return parent::createEntity($entityFqcn);
    }
}
