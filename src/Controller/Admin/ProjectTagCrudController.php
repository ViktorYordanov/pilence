<?php

namespace App\Controller\Admin;

use App\Entity\ProjectTag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProjectTagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectTag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.project_tag.singular')
            ->setEntityLabelInPlural('admin.project_tag.plural')
            ->setPageTitle('index', 'admin.project_tag.list')
            ->setPageTitle('new', 'admin.project_tag.create')
            ->setPageTitle('edit', 'admin.project_tag.edit');
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('project')
            ->setLabel('admin.project_tag.project');

        yield AssociationField::new('tag')
            ->setLabel('admin.project_tag.tag');
    }
}
